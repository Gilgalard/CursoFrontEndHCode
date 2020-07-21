<?php include_once("header.php") ?>
<section>
    <div class="container" id="destaque-produtos-container">
        <div id="destaque-produtos">
            <div class="col-sm-6 col-imagem">
                <img src="img/produtos/<?=$produto['foto_principal']?>" alt="<?=$produto['nome_prod_longo']?>">
            </div>
            <div class="col-sm-6 col-descricao">
                <h2><?=$produto['nome_prod_longo']?></h2>
                <div class="box-valor">
                    <div class="text-boleto text-arial-cinza">No boleto por</div>
                    <div class="text-por text-arial-cinza">por</div>
                    <div class="text-reais text-roxo">R$</div>
                    <div class="text-valor text-roxo"><?=$produto['preco_inteiro']?></div>
                    <div class="text-valor-centavos text-roxo">,<?=$produto['preco_centavos']?></div>
                    <div class="text-parcelas text-arial-cinza">ou em até <?=$produto['parcelas_qtde']?>x de R$ <?=$produto['parcelas_valor']?></div>
                    <div class="text-totalprazo text-arial-cinza">total a prazo R$ <?=$produto['parcelas_total_valor']?></div>
                </div>
                <a href="carrinhoAdd-<?=$produto['id_prod']?>" class="btn btn-comprar text-roxo"><i class="fa fa-shopping-cart"></i>compre agora</a>
            </div>
        </div>
    </div>
</section>
<?php include_once("footer.php") ?>
<script>
angular.module("shop", []).controller("destaque-controller", function($scope, $http) {
    $scope.produtos = [];

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