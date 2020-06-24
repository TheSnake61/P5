//Article object

var Article = {

    id: null,
    title: null,
    content: null,
    createdAt: null,
    imageFilename: null,
    date: null,
    maxpage: null,
    pagen: null,




    //init method
    initArticle: function () {

        this.getMaxPage();

        $("#firstpage").click(() => {
            this.pagen = this.maxpage;
            this.htmlPrint();
        });
        $("#lastpage").click(() => {
            this.pagen = 1;
            this.htmlPrint();
        });
        $("#prevpage").click(() => {
            this.nextPage();
        });
        $("#nextpage").click(() => {
            this.prevPage();
        });




    },

    htmlPrint: function () {  //insert infos into html
        console.log(this);
        this.ajaxGet("/articless?page=" + this.pagen, (data) => {
            $('.article').remove();
            articleList = JSON.parse(data);

            articleList.reverse().forEach((articleInfo) => {

                var $div = $('.articles');
                this.date = new Date(articleInfo.createdAt.date);
                var html = articleTemplate(articleInfo);
                $div.append($.parseHTML(html));
            });
        });
    },

    // Ajax get method
    ajaxGet: function (url, callback) {
        req = new XMLHttpRequest();
        req.open("GET", url);
        req.addEventListener("load", function () {
            if (req.status >= 200 && req.status < 400) {
                callback(req.responseText);
            } else {
                console.error(req.status + " " + req.statusText + " " + url);
            }
        });
        req.addEventListener("error", function () {
            console.error("Erreur réseau avec l'URL " + url);
        });
        req.send(null);
    },

    // getMaxPage: function () {
    //     this.ajaxGet("/articlesall", (data1) => {
    //         articlesAll = JSON.parse(data1);
    //         this.maxpage = Math.ceil(articlesAll.length / 5);
    //         this.pagen = this.maxpage;
    //         this.htmlPrint();
    //     });
    // },

    getMaxPage: function () {
        articlesAll = $('#res-result').data('res');
        console.log(articlesAll);
        this.maxpage = Math.ceil(articlesAll / 5);
        this.pagen = this.maxpage;
        this.htmlPrint();

    },



    nextPage: function () {
        if (this.pagen <= this.maxpage) {

            this.pagen++;
            this.htmlPrint();
        } else {
            this.pagen = this.maxpage;
        }
    },

    prevPage: function () {
        if (this.pagen > 1) {

            this.pagen--;
            this.htmlPrint();
        } else {
            this.pagen = 1;
        }
    },
}

Article.initArticle();

// plutot créer les elements
const articleTemplate = (articleInfo) =>
    `<div class="article">
    <hr class="my-4">
			<article>
				<h2>${ articleInfo.title}</h2>
				<div class="metadata">ecrit le
					${Article.date}</div>
				<div class="content">
					<img src="images/${ articleInfo.imageFilename}" class="img-fluid rounded mx-auto" alt="">
					<hr class="my-2">
					${articleInfo.content.slice(0, 200)}...<br>
					<div id="spacer"></div>
					<a href="${Routing.generate('article', { 'id': articleInfo.id })}" class="btn btn-primary btn-sm">Lire la suite</a>
				</div>
			</article>
            <hr class="my-2">
            </div>`;

