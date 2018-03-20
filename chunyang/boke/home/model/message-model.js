$(document).ready(function () {

    function MessageModel() {

        this.code = null;
        this.onOff = true;
        this.page = 1;

        this.init = function() {
            this.getMessageList();
            this.clickForm();
            this.checkCode();
        }

        this.getMessageList = function (page) {
            this.page = page ? page : 1;
            var that = this;
            $.ajax({
                url: baseUrl + 'home/message?page='+this.page,
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.onOff = true;
                    if (res.data.length) {
                        cancelReply();
                        that.setMessageList(res);
                    }
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setMessageList = function (data) {
            var html = '';
            var list = data.data;
            for (d in list) {
                html += template('messageListTemplate', list[d]);
            }
            document.getElementById('messageList').innerHTML = html;

            this.setMessageListPage(data.total,data.per_page,data.current_page);
        }

        this.setMessageListPage = function(total,size,current) {
            var totalPage = Math.ceil(total/size);
            if (totalPage > 1) {
                var html = '';
                for (i = 1; i<=totalPage; i++) {
                    if (i == current) {
                        html += '<span data-page="'+ i + '">' + i + '</span>';
                    } else {
                        html += '<a class="clickPage"  href="javascript:;" data-page="'+ i + '">' + i + '</a>';
                    }
                } 
                if (!(totalPage == current)) {
                    html += "<a  class='clickPage' href='javascript:;' data-page='"+(Number(current) + 1) +"'>&raquo;</a>";
                }
                
                document.getElementById('pagenavi').innerHTML = html;
                var obj = document.getElementById('cancel-reply');
                this.clickPage();
            }   
        }

        this.clickPage = function() {
            var that = this;
            $('.clickPage').on('click',function(){
                var page = $(this).attr('data-page');
                if (that.onOff) {
                    that.onOff = false;
                    that.getMessageList(page);
                }
            });
        }

        this.clickForm = function(){
            var that = this;
            $('#comment_submit').on('click',function(){

                var code_math = $("#code_math").val();
                $.post("./code/chk_code.php?act=math",{code:code_math},function(msg){
                    if(msg==1){
                        that.onOff = false;
                        var d = {};
                        var t = $('form').serializeArray();
                        $.each(t, function() {
                          d[this.name] = this.value;
                        });
        
                        $("input[name='comname']").val('');
                        $("input[name='commail']").val('');
                        $("input[name='comurl']").val('');
                        $("#comment").val('');
        
                        that.validate(d);
                    }else{
                        $("#getcode_math").attr("src",'./code/code_math.php?' + Math.random());
                        alert("验证码错误！");
                    }
                });

            });
        }

        this.validate = function(data) {
            if (!data.comname || data.comname.length > 10) {
                alert('昵称不能为空或者超出长度');
            } 
            else if (!data.comment || data.comment > 100) {
                alert('内容不能为空或者超出长度');
            } 
            else {
                this.postMessage(data);
            }        
        }

        this.postMessage = function(data) {
            var that = this;
            $.ajax({
                url: baseUrl + 'home/message/add',
                method: 'post',
                dataType: 'json',
                data:data,
                success: function (res) {
                    that.onOff = true;
                    if (res.type) {
                        alert('留言成功');
                        $("#getcode_math").attr("src",'./code/code_math.php?' + Math.random());
                        that.getMessageList(that.page);
                    }
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.checkCode = function() {
            $("#getcode_math").click(function(){
                $(this).attr("src",'./code/code_math.php?' + Math.random());
            });
        }

    }

    var messageModel = new MessageModel();
    messageModel.init();
})
