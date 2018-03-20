$(document).ready(function () {

    function HomeModel() {

        this.onOff = true;
        this.type = 'all';
        this.onOffUrl = true; 

        this.init = function() {
            this.getNewsList();
            this.getClassify();
        }

        this.getNewsList = function (flag,page) {
            flag = flag ? this.type = flag : 'all';
            if (this.onOffUrl) {
                if ($.getUrlParam('id')) {
                   flag = $.getUrlParam('id');
                   this.onOffUrl = false;
                }
            }
            this.type = flag;
            page = page ? page : 1;
            var that = this;
            $.ajax({
                url: baseUrl + 'home/news/' + this.type + '?page='+page,
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.onOff = true;
                    that.setNewsList(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setNewsList = function (data) {
            var html = '';
            var list = data.data;
            for (d in list) {
                if (list[d].content.length > 200) {
                    list[d].content = list[d].content.substring(0,200) + '...';
                }
                html += template('newsListTemplate', list[d]);
            }
            document.getElementById('newsList').innerHTML = html;
            this.setNewsListPage(data.total,data.per_page,data.current_page);
        }

        this.setNewsListPage = function(total,size,current) {
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
                    that.getNewsList(that.type,page);
                }
            });
        }

        this.getClassify = function() {
            var that = this;
            $.ajax({
                url: baseUrl + 'home/classify',
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.setClassify(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setClassify = function(data){
            var html = '';
            for (d in data) {
                html += template('classifyTemplate', data[d]);
            }
            document.getElementById('blogsort').innerHTML = html;
            this.clickClassify();
        }

        this.clickClassify = function() {
            var that = this;
            $('.classify').on('click',function(){
                var id = $(this).attr('data-id');
                if (that.onOff) {
                    that.onOff = false;
                    that.getNewsList(id);
                }
            })
        }

    }

    var homeModel = new HomeModel();
    homeModel.init();
})
