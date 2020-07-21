<?php
require 'inc/Slim-2.x/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get(
    '/',
    function () {
        require_once("view/index.php");
    }
);

$app->get(
    '/videos',
    function () {
        require_once("view/videos.php");
    }
);

$app->get(
    '/shop',
    function () {
        require_once("view/shop.php");
    }
);

require_once 'inc/configuration.php';

$app->get(
    '/produtos',
    function () {
        $sql = new Sql();
        $data = $sql->select("select * from tb_produtos where preco_promorcional > 0 order by preco_promorcional desc limit 3");

        foreach ($data as &$produto) {
            $preco = $produto['preco']; 
            $centavos = explode(".", $preco);
            $produto['preco_inteiro'] = number_format($preco, 0, ",", ".");
            $produto['preco_centavos'] = end($centavos);
            $produto['parcelas_qtde'] = 10;
            $produto['parcelas_valor'] = number_format($preco /  $produto['parcelas_qtde'], 2, ',', '.');
            $produto['parcelas_total_valor'] = number_format($preco, 2, ',', '.');
        }

        echo json_encode($data);
    }
);

$app->get(
    '/produtos-mais-buscados',
    function () {
        $sql = new Sql();
        $data = $sql->select("
        SELECT 
        tb_produtos.id_prod,
        tb_produtos.nome_prod_curto,
        tb_produtos.nome_prod_longo,
        tb_produtos.codigo_interno,
        tb_produtos.id_cat,
        tb_produtos.preco,
        tb_produtos.peso,
        tb_produtos.largura_centimetro,
        tb_produtos.altura_centimetro,
        tb_produtos.quantidade_estoque,
        tb_produtos.preco_promorcional,
        tb_produtos.foto_principal,
        tb_produtos.visivel,
        cast(avg(review) as dec(10,2)) as media, 
        count(id_prod) as total_reviews
        FROM tb_produtos 
        INNER JOIN tb_reviews USING(id_prod) 
        GROUP BY 
        tb_produtos.id_prod,
        tb_produtos.nome_prod_curto,
        tb_produtos.nome_prod_longo,
        tb_produtos.codigo_interno,
        tb_produtos.id_cat,
        tb_produtos.preco,
        tb_produtos.peso,
        tb_produtos.largura_centimetro,
        tb_produtos.altura_centimetro,
        tb_produtos.quantidade_estoque,
        tb_produtos.preco_promorcional,
        tb_produtos.foto_principal,
        tb_produtos.visivel
        LIMIT 4");

        foreach ($data as &$produto) {
            $preco = $produto['preco']; 
            $centavos = explode(".", $preco);
            $produto['preco_inteiro'] = number_format($preco, 0, ",", ".");
            $produto['preco_centavos'] = end($centavos);
            $produto['parcelas_qtde'] = 10;
            $produto['parcelas_valor'] = number_format($preco /  $produto['parcelas_qtde'], 2, ',', '.');
            $produto['parcelas_total_valor'] = number_format($preco, 2, ',', '.');
        }

        echo json_encode($data);
    }
);

$app->get(
    '/produto-:id_prod',
    function ($id_prod) {
        $sql = new Sql();
        $produtos = $sql->select("select * from tb_produtos where id_prod = $id_prod");

        foreach ($produtos as &$field) {
            $preco = $field['preco']; 
            $centavos = explode(".", $preco);
            $field['preco_inteiro'] = number_format($preco, 0, ",", ".");
            $field['preco_centavos'] = end($centavos);
            $field['parcelas_qtde'] = 10;
            $field['parcelas_valor'] = number_format($preco /  $field['parcelas_qtde'], 2, ',', '.');
            $field['parcelas_total_valor'] = number_format($preco, 2, ',', '.');
        }

        $produto = $produtos[0];

        require_once("view/shop-produto.php");
    }
);

$app->get(
    '/cart',
    function() {
        require_once("view/cart.php");
    }
);

$app->get('/carrinho-dados', function() {
    $sql = new Sql();
    $result = $sql->select("call sp_carrinhos_get('".session_id()."')");

    $carrinho = $result[0];
    $carrinho['subtotal_car'] = number_format((float)$carrinho['subtotal_car'], 2, ',', '.');
    $carrinho['frete_car'] = number_format((float)$carrinho['frete_car'], 2, ',', '.');
    $carrinho['total_car'] = number_format((float)$carrinho['total_car'], 2, ',', '.');

    $sql = new Sql();
    $carrinho['produtos'] = $sql->select("call sp_carrinhosprodutos_list(".$carrinho['id_car'].")");
    $carrinho['storedProcedure'] ="call sp_carrinhosprodutos_list(".$carrinho['id_car'].")";
    
    echo json_encode($carrinho);
});

$app->post('/carrinho', function() {
    $request_body = json_decode(file_get_contents('php://input'), true);
    var_dump($request_body);
});

$app->get('/carrinhoAdd-:id_prod', function($id_prod) {
    $sql = new Sql();
    $result = $sql->select("call sp_carrinhos_get('" . session_id() . "')");
    $carrinho = $result[0];
    
    $sql = new Sql();
    $sql->query("call sp_carrinhosprodutos_add(".$carrinho['id_car'].", ".$id_prod.")");
    
    header("Location: cart");
    exit;
});

$app->delete("/carrinhoRemoveAll-:id_prod", function($id_prod) {
    $sql = new Sql();
    $result = $sql->select("call sp_carrinhos_get('" . session_id() . "')");
    $carrinho = $result[0];
    
    $sql = new Sql();
    $sql->query("call stpRemoverProdutoCarrinho(".$carrinho['id_car'].", ".$id_prod.")");
});

$app->post("/carrinho-produto", function() {
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = new Sql();
    $result = $sql->select("call sp_carrinhos_get('".session_id()."')");
    $carrinho = $result[0];
    
    $sql = new Sql();
    $sql->query("call sp_carrinhosprodutos_add(".$carrinho['id_car'].", ".$data['id_prod'].")");    
});

$app->delete("/carrinho-produto", function() {
    $data = json_decode(file_get_contents("php://input"), true);

    $sql = new Sql();
    $result = $sql->select("call sp_carrinhos_get('".session_id()."')");
    $carrinho = $result[0];
    
    $sql = new Sql();
    $sql->query("call sp_carrinhosprodutos_rem(".$carrinho['id_car'].", ".$data['id_prod'].")");    
});

$app->get('/calcular-frete-:cep', function($cep) {
    $sql = new Sql();
    $result = $sql->select("call sp_carrinhos_get('".session_id()."')");
    $carrinho = $result[0];
    
    $sql = new Sql();
    $produtos = $sql->select("call sp_carrinhosprodutosfrete_list(".$carrinho['id_car'].")");    

    $peso = 0;
    $comprimento = 0;
    $altura = 0;
    $largura = 0;
    $valor = 0.0;

    foreach($produtos as $produto) {
        $peso =+ $produto['peso'];
        $comprimento =+ $produto['comprimento'];
        $altura =+ $produto['altura'];
        $largura =+ $produto['largura'];
        $valor =+ $produto['preco'];       
    };

    require_once("inc/frete/frete.php");
    
    $cep = trim(str_replace('-', '', $cep));
    $frete = new Frete(
        $cepDeOrigem = '01418100',
        $cepDeDestino = $cep,
        $peso,
        $comprimento,
        $altura,
        $largura,
        $valor
    );

    $sql = new Sql();
    $sql->query("update tb_carrinhos set cep_car = '".$cep."', frete_car = ".$frete->getValor().", prazo_car = ".$frete->getPrazoEntrega()." where id_car = ".$carrinho['id_car']);

    echo json_encode(array(
        'valor_frete'=>$frete->getValor(),
        'prazo'=>$frete->getPrazoEntrega()
    ));
});

$app->run();