# consultarOperadora
Essa classe permite a consulta de operadoras por telefone de forma gratuita.

## Getting Started
Faça o download dessa classe para utilizar no seu projeto ou executar o arquivo direto do seu servidor local (localhost)

### Como utilizar

Você pode utilizar o arquivo direto pelo navegador passando os telefones como parâmetro:
http://localhost/operadora.php?tel1=016999999999

Ou você pode importar a classe como um plugin para realizar as consultas no seu projeto:


```
require_once(operadora.php);
```

Após isso, basta instânciar e se divertir:

```
$objeto = new Operadora();
$telefones = ['016999999999','016988888888','01122220000']
$retorno = $objeto->get_operadora($telefones);
var_dump($retorno);
```

## Autores

* **Dimitry Duarte** - (https://github.com/dimitryduarte?tab=repositories)
* **Marco Antônio Jr** - (https://github.com/marcoan1105?tab=repositories)

## Contribuição
Todas as consultas somente são possíveis devido ao serviço de consulta disponibilizado pelo site:
Consulta Numero - (http://consultanumero.info)
Deixamos aqui nossos agradecimentos.