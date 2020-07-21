<?php include_once("header.php") ?>
<section ng-controller="destaque-controller">
    <div class="container" id="destaque-produtos-container">
        <div id="destaque-produtos" class="owl-carousel owl-theme">
            <div class="item" ng-repeat="produto in produtos">
                <div class="item-inner">
                    <div class="col-sm-6 col-imagem">
                        <img src="img/produtos/{{produto.foto_principal}}" alt="{{produto.nome_prod_longo}}">
                    </div>
                    <div class="col-sm-6 col-descricao">
                        <h2>{{produto.nome_prod_longo}}</h2>
                        <div class="box-valor">
                            <div class="text-boleto text-arial-cinza">No boleto por</div>
                            <div class="text-por text-arial-cinza">por</div>
                            <div class="text-reais text-roxo">R$</div>
                            <div class="text-valor text-roxo">{{produto.preco_inteiro}}</div>
                            <div class="text-valor-centavos text-roxo">,{{produto.preco_centavos}}</div>
                            <div class="text-parcelas text-arial-cinza">ou em até {{produto.parcelas_qtde}}x de R$ {{produto.parcelas_valor}}</div>
                            <div class="text-totalprazo text-arial-cinza">total a prazo R$ {{produto.parcelas_total_valor}}</div>
                        </div>
                        <a href="produto-{{produto.id_prod}}" class="btn btn-comprar text-roxo"><i class="fa fa-shopping-cart"></i>compre agora</a>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="btn-destaque-prev" class="btn-destaque-nav"><i class="fa fa-angle-left"></i></button>
        <button type="button" id="btn-destaque-next" class="btn-destaque-nav"><i class="fa fa-angle-right"></i></button>
    </div>
    <div id="promocoes" class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="box-promocao box-1">
                    <p>escolha por desconto</p>
                </div>
            </div>
            <div class="col-md-10">
                <div class="row-fluid">
                    <div class="col-md-3">
                        <div class="box-promocao">
                            <div class="text-ate">até</div>
                            <div class="text-numero">40</div>
                            <div class="text-porcento">%</div>
                            <div class="text-off">off</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-promocao">
                            <div class="text-ate">até</div>
                            <div class="text-numero">60</div>
                            <div class="text-porcento">%</div>
                            <div class="text-off">off</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-promocao">
                            <div class="text-ate">até</div>
                            <div class="text-numero">80</div>
                            <div class="text-porcento">%</div>
                            <div class="text-off">off</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box-promocao">
                            <div class="text-ate">até</div>
                            <div class="text-numero">85</div>
                            <div class="text-porcento">%</div>
                            <div class="text-off">off</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mais-buscados" class="container">
        <div class="row text-center title-default-roxo">
            <h2>os mais buscados</h2>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-3" ng-repeat="produto in buscados">
                <div class="box-produto-info">
                    <a href="produto-{{produto.id_prod}}">
                        <img src="img/produtos/{{produto.foto_principal}}" alt="{{produto.nome_prod_longo}}" class="produto-img">
                        <h3>{{produto.nome_prod_longo}}</h3>
                        <div class="estrelas" data-score="{{produto.media}}"></div>
                        <div class="text-qtd-reviews text-arial-cinza">({{produto.total_reviews}})</div>
                        <div class="text-valor text-roxo">R$ {{produto.parcelas_total_valor}}</div>
                        <div class="text-parcelados text-arial-cinza">{{produto.parcelas_qtde}}x de R$ {{produto.parcelas_valor}} sem juros</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include_once("footer.php") ?>
<script>
angular.module("shop", []).controller("destaque-controller", function($scope, $http) {
    $scope.produtos = [];
    $scope.buscados= [];

    var initCarousel = function() {
        var owlDestaque = $("#destaque-produtos");

        $(owlDestaque).owlCarousel({
            autoPlay: 5000,
            items: 1,
            singleItem: true,
            rewind: true
        });

        $("#btn-destaque-prev").on("click", function() {
            owlDestaque.trigger('prev.owl.carousel', [300]);
        });

        $("#btn-destaque-next").on("click", function() {
            owlDestaque.trigger('next.owl.carousel');
        });
    };

    $http({
        method: 'GET',
        url: 'produtos'
    }).then(function successCallback(response) {
        $scope.produtos = response.data;
        setTimeout(initCarousel, 500);
    }, function errorCallback(response) {

    });

    var initEstrelas = function() {
        $(".estrelas").each(function() {
            $(this).raty({
                starHalf: 'lib/raty/lib/images/star-half.png',
                starOff: 'lib/raty/lib/images/star-off.png',
                starOn: 'lib/raty/lib/images/star-on.png',
                score: parseFloat($(this).data("score"))
            });
        });
    };

    $http({
        method: 'GET',
        url: 'produtos-mais-buscados'
    }).then(function successCallback(response) {
        $scope.buscados = response.data;
        setTimeout(initEstrelas, 500);
    }, function errorCallback(response) {

    });
});

$(function(){

});
</script>