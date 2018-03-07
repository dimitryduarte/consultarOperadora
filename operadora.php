<?php

//CLASSE PARA CONSULTAR OPERADORA DE QUALQUER TELEFONE
//BY DIMITRY DUARTE E MARCO ANTONIO JR
for ($i=1; $i <= 10 ; $i++) { 
    if (isset($_GET['tel'.$i])) {
       $numeroTelefone[] = trim($_GET['tel'.$i]); 
    }
}
$exec = new Operadora();
$resultado = $exec->get_operadora($numeroTelefone);
$retornFinal = array();
foreach ($resultado as $telefone) {
    $retornoJson = array();
    $retornoJson[] = $telefone['numero'];
    $retornoJson[] = $telefone['operadora'];
    $retornFinal[] = $retornoJson;
}

echo json_encode($retornFinal);

Class Operadora{

function get_operadora(array $telefones){

    $curlIndividual = [];

    $curlTodos = curl_multi_init();

    foreach($telefones as $telefone){

        $curlIndividual[$telefone] = curl_init('http://consultanumero.info/consulta');

        curl_setopt_array($curlIndividual[$telefone], [
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0',
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => 'tel='.$telefone,
            CURLOPT_RETURNTRANSFER => 1
        ]);

        curl_multi_add_handle($curlTodos, $curlIndividual[$telefone]);

    }


    $Executando = 1;

    while($Executando> 0){
        curl_multi_exec($curlTodos, $Executando);
        curl_multi_select($curlTodos);
    }
 	
 	$resultado = "";
 	$retorno = [];

    foreach($curlIndividual as $telefone => $curl){
        $resultado = curl_multi_getcontent($curl);
        $resultado = str_replace("<button class=\"li\" onclick=\"ga('send', 'event', 'Consulta', 'Ligar'); window.location = 'tel:".$telefone."'\">Ligar</button>","",$resultado);
        $resultado = str_replace("<button class=\"nc\" onclick=\"ga('send', 'event', 'Consulta', 'Nova'); window.location = '/'\">Nova Consulta</button>","",$resultado);        
        $resultado = str_replace("<div class=\"ajuste\"></div>
</div>","",$resultado);
        $resultado = str_replace("</html>","",$resultado); 
        $resultado = str_replace("<!--
<script type=\"text/javascript\">
	var google_conversion_id = 964461137;
	var google_conversion_language = \"en\";
	var google_conversion_format = \"3\";
	var google_conversion_color = \"ffffff\";
	var google_conversion_label = \"yIn9CNeoogoQ0YTyywM\";
	var google_remarketing_only = false;
</script>
<script type=\"text/javascript\" src=\"//www.googleadservices.com/pagead/conversion.js\"></script>
<noscript>
	<div style=\"display: inline\"><img height=\"1\" width=\"1\" style=\"border-style: none\" alt=\"\" src=\"//www.googleadservices.com/pagead/conversion/964461137/?label=yIn9CNeoogoQ0YTyywM&amp;guid=ON&amp;script=0\"/></div>
</noscript>
-->","",$resultado);        
        $resultado = str_replace("<div class=\"prop\">
		<ins class=\"adsbygoogle\" style=\"display: inline-block\" data-analytics-uacct=\"UA-16052289-33\" data-ad-client=\"ca-pub-3806187316222201\" data-ad-slot=\"9873102520\"></ins>
		<script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
	</div>","",$resultado);        
        $resultado = str_replace("<div id=\"rdp\">
	<p>&copy; 2018 Consulta Número | <a href=\"/sobre\">Sobre</a> | <a href=\"/politica-de-privacidade\">Privacidade</a></p>
</div>","",$resultado);        
        $resultado = str_replace("<!DOCTYPE html>

<html lang=\"pt-br\">

<head>
	<meta charset=\"utf-8\" />
	<title>Consulta Número</title>
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\" />
	<meta name=\"apple-mobile-web-app-capable\" content=\"yes\" />
	<meta property=\"fb:app_id\" content=\"519584384854184\" />
	<link rel=\"shortcut icon\" href=\"/favicon.ico\" />
	<link rel=\"stylesheet\" href=\"/css/base.css?1409782940\" media=\"all\" />
	<link rel=\"prerender\" href=\"http://consultanumero.info/\" />
	<script src=\"/js/base.js?1407968504\" async></script>
	<script src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\" async></script>
	<script>
		(function(i,s,o,g,r,a,m){i[\"GoogleAnalyticsObject\"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,\"script\",\"//www.google-analytics.com/analytics.js\",\"ga\");
		ga(\"create\", \"UA-16052289-33\", \"auto\");
		ga(\"send\", \"pageview\");
	</script>
</head>

<body class=\"int consulta\">

<div id=\"geral\">
<div id=\"cab\">
	<h1><a href=\"/\">Consulta Número</a></h1>
	<p>Descubra a operadora atual de qualquer número, celular ou telefone fixo!</p>
</div>
","",$resultado);
        $resultado = str_replace("<body>","",$resultado);
        $resultado = str_replace("</body>","",$resultado);


    	$operadora = "";

        if(preg_match('/<img src="(.*?)" alt="(.*?)" title="(.*?)" \/>/', $resultado, $matches)) {            
            $operadora = $matches[2];
        }

        $resultado = preg_replace('/<img src="(.*?)" alt="(.*?)" title="(.*?)" \/>/', "", $resultado);

        //$resultado = str_replace("<img src=\"/img/op/".strtolower($operadora).".png\" alt=\"".strtoupper($operadora)."\" title=\"".strtoupper($operadora)."\" />","",$resultado);

        $retorno[] = [
        	"operadora" => $operadora,
        	"numero" => $telefone,
        	"retorno" => $resultado
        ];
    }

    return $retorno; 

}

}
?>

