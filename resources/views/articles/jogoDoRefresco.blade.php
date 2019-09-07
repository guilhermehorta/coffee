@extends('layouts.app')

@section('content')
<article id="jogoDoCafe">
<h1>Simulação de gestão integrada</h1>
<figure>
    <img src="{{ asset('images/jogoRefresco-vistaGeral.jpg') }}">
    <figcaption>Fig1. - Vista do jogo em sala.</figcaption>
</figure>
<p>
	O Simulador de Gestão Integrada desenvolvido por Manuel Oliveira pretende integrar os conhecimentos de gestão nas diferentes áreas e de uma forma realista por em prática um conjunto alargado de competências.
</p>
<p>   
	A gestão das empresas é colocada nas mãos dos participantes com o objectivo de maximizar o lucro ao longo de um número variável de momentos de compra e venda. Além das empresas produtoras de refresco que competem entre si, existe ainda um banco, uma empresa de transportes, uma empresa fornecedora de água purificada e uma de venda de café, açúcar e também garrafas.
</p>
<p> 
	Todo o exercício tem por objectivo ser o mais realista possível e os resultados surgem na forma de lucro obtido à semelhança do mundo real. Cada empresa tem que fazer antecipadamente a gestão de stocks. A produção é feita em cubas de pequena dimensão, o que limita a capacidade produtiva da empresa, e tem que ser feita cumprindo rigorosamente uma composição especificada, sob pena de ser rejeitado pelo cliente. Só após a produção, a empresa tem oportunidade de submeter propostas aos potenciais clientes. Em cada ciclo, cada cliente irá adquirir uma unidade de refresco. Cada cliente irá escolher como fornecedor a empresa que apresentar o custo mais baixo e, em caso de empate, a que apresentou a proposta em primeiro lugar. Os custos de transporte são assumidos pela empresa produtora de refresco, tanto na aquisição de matéria prima como na distribuição de produto.
</p>
<p>
	Durante o jogo muitas são as decisões que comprometem a obtenção do lucro máximo e muitos são os factores que levam os participantes a ter de reflectir durante a simulação. Este jogo prepara-nos para a gestão estratégica e operacional do negócio.
</p>
</article>

@endsection