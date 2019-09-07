@extends('layouts.app')

@section('content')
<article id="jogoDoCafe">
<h1>Simulação de gestão integrada</h1>
<figure>
    <img src="{{ asset('images/bank.jpg') }}">
    <figcaption>Fig1. - Linda imagem.</figcaption>
</figure>
<p>
	O Simulador de Gestão Integrada desenvolvido por Manuel Oliveira pretende integrar os conhecimentos de gestão nas diferentes áreas e de uma forma realista por em prática um conjunto alargado de competências.
</p>
<p>   
A gestão das empresas é colocada nas mãos dos participantes com o objectivo de maximizar o lucro em 5 momentos de compra e venda (5 ciclos). Existem quatro empresas produtoras de refresco, um banco, uma empresa de transportes, uma empresa fornecedora de água purificada e uma de venda de café e açúcar à dose e também garrafas.
</p>
<p> 
Todo o exercício tem por objectivo ser o mais realista possível e os resultados surgem na forma de lucro obtido à semelhança do mundo real.  
Faz-se a produção e venda de refresco de café sendo que 1 litro do produto é constituído por água, uma dose de café e duas doses de açúcar.
Existem 35 clientes para abastecer, em cada ciclo terão de ser abastecidos com uma garrafa de refresco. Abastece o cliente quem apresentar o custo mais baixo e em caso de empate ganha a empresa quem apresentou primeiro a proposta. 
Os componentes são misturados em cubas de dois litros, sendo que cada empresa produtora de refresco tem duas cubas para poder gerir a sua produção.
</p>
</article>

@endsection