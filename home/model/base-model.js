(function ($) {
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }
})(jQuery);

//var baseUrl = "http://localhost/eclipse/chunyang/boke/service/public/index.php/api/";
 var baseUrl = "https://api.oooco.cn/api/";

$(document).ready(function () {

    function BaseModel() {

        this.init = function() {
            this.getClassify();
            this.getNewWeiyu();
            this.getNewsTitle();
            this.getLink();
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
        }

        this.getNewWeiyu = function() {
            var that = this;
            $.ajax({
                url: baseUrl + 'home/weiyu',
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.setNewWeiyu(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setNewWeiyu = function(data) {
            var html = '';
            for (d in data) {
                if (data[d].content.length > 50) {
                    data[d].content = data[d].content.substring(0,50) + '...';
                }
                html += template('twitterTemplate', data[d]);
            }
            document.getElementById('twitter').innerHTML = html;
        }

        this.getNewsTitle = function() {
            var that = this;
            $.ajax({
                url: baseUrl + 'home/news/title',
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.setNewsTitle(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setNewsTitle = function(data) {
            //绑定最新文章
            var newsTitle = '';
            var newsObj = data.news;
            for (d in newsObj) {
                if (newsObj[d].title.length > 15) {
                    newsObj[d].title = newsObj[d].title.substring(0,15) + '...';
                }
                newsTitle += template('newlogTemplate', newsObj[d]);
            }
            document.getElementById('newlog').innerHTML = newsTitle;

            //绑定热门文章
            var newsTitle = '';
            var newsObj = data.hot;
            for (d in newsObj) {
                if (newsObj[d].title.length > 15) {
                    newsObj[d].title = newsObj[d].title.substring(0,15) + '...';
                }
                newsTitle += template('hotlogTemplate', newsObj[d]);
            }
            document.getElementById('hotlog').innerHTML = newsTitle;
        }

        this.getLink = function(){
            var that = this;
            $.ajax({
                url: baseUrl + 'home/link',
                method: 'get',
                dataType: 'json',
                success: function (res) {
                    that.setLink(res);
                },
                error: function (err) {
                    console.log('错误');
                }
            })
        }

        this.setLink = function(data) {
             //绑定最新文章
             var html = '';
             for (d in data) {
                html += template('FriendshipLinkTemplate', data[d]);
             }
             document.getElementById('FriendshipLink').innerHTML = html;
        }
    }

    var baseModel = new BaseModel();
    baseModel.init();
})
