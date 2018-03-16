$(document).ready(function () {
    function NewsDetailModel() {

        this.onOff = true;

        this.init = function () {
            this.getNewsDetail();
            this.getNewsByRand();
        }

        this.getNewsDetail = function (id) {
            var newsId = id || $.getUrlParam('id');
            var that = this;
            $.ajax({
                url: baseUrl + 'home/news/detail/' + newsId,
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.onOff = true;
                    that.setNewsDetail(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setNewsDetail = function (data) {

            var detailNav = {
                id: data.id,
                title: data.title,
                type: data.type
            }
            var nav = template('placeTemplate', detailNav);
            document.getElementById('place').innerHTML = nav;

            var html = template('newsDetailTemplate', data);
            document.getElementById('newsDetail').innerHTML = html;

            this.getDetailnav(data.id);
        }

        this.getDetailnav = function (id) {
            var that = this;
            $.ajax({
                url: baseUrl + 'home/news/detailNav/' + id,
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.setDetailnav(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setDetailnav = function (data) {
            html = '';
            if (data.prev) {
                html += '<a class="l onclickDetailnav" href="javascript:;" data-id="' + data.prev.id + '">上一篇：' + data.prev.title + '</a>';
            }
            if (data.next) {
                html += '<a class="r onclickDetailnav" href="javascript:;" data-id="' + data.next.id + '">下一篇：' + data.next.title + '</a>';
            }
            html += '<div class="clear"></div>';
            document.getElementById('post_nav').innerHTML = html;

            this.clickDetailnav();
        }

        this.clickDetailnav = function () {
            var that = this;
            $('.onclickDetailnav').on('click', function () {
                var id = $(this).attr('data-id');
                if (that.onOff){
                    that.onOff = false;
                    that.getNewsDetail(id);
                }
                
            });
        }
        
        this.getNewsByRand = function() {
            var that = this;
            $.ajax({
                url: baseUrl + 'home/news/rand',
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.setNewsByRand(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setNewsByRand = function(data) {
            var html = '';
            for (d in data) {
                html += template('cainixihuanTemplate', data[d]);
            }
            document.getElementById('cainixihuan').innerHTML = html;
            this.clickDetailnav();
        }
    }
    
    var newsDetailModel = new NewsDetailModel();
    newsDetailModel.init();
})
