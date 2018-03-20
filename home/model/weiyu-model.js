$(document).ready(function () {

    function WeiYuModel() {

        this.onOff = true;

        this.init = function() {
            this.getWeiYusList();
        }

        this.getWeiYusList = function (page) {
            page = page ? page : 1;
            var that = this;
            $.ajax({
                url: baseUrl + 'home/weiyu/list?page='+page,
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.onOff = true;
                    that.setWeiYusList(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setWeiYusList = function (data) {
            var html = '';
            var list = data.data;
            for (d in list) {
                html += template('weiyuListTemplate', list[d]);
            }
            document.getElementById('weiyuList').innerHTML = html;
            this.setWeiYuListPage(data.total,data.per_page,data.current_page);
        }

        this.setWeiYuListPage = function(total,size,current) {
            var totalPage = Math.ceil(total/size);
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
            this.clickPage();
        }

        this.clickPage = function() {
            var that = this;
            $('.clickPage').on('click',function(){
                var page = $(this).attr('data-page');
                if (that.onOff) {
                    that.onOff = false;
                    that.getWeiYusList(page);
                }
            });
        }

    }

    var weiyuModle = new WeiYuModel();
    weiyuModle.init();
})
