$(document).ready(function()
{
    $(".panel-btn").click(function(){verDetalhes($(this));});


/*******************************************
*
* Mostrar e esconder os paineis informativos
*
********************************************
*/
    function verDetalhes(obj)
    {
        var id = obj.data('name');
        var group = obj.data('group');
         
        if ($("#"+group+" #detalhes-"+id).css('display') == 'block') // está aberto
        {
            // Fechar todos os detalhes
            $("#"+group+" .detalhes").slideUp(200);
            // Eliminar todas as classes "active" a todos os botões
            $("#"+group+" .panel-btn.active").removeClass("active");

        }
        else // está fechado
        {
            // Fechar todos os detalhes
            $("#"+group+" .detalhes").slideUp(200);
            // Eliminar todas as classes "active" a todos os botões
            $("#"+group+" .panel-btn.active").removeClass("active");
            // e depois mostrar os detalhes da row que tem o id certo
            $("#"+group+" #detalhes-"+id).slideDown(200);
            // Adicionar a classe "active" ao botão correspondente
            $("#"+group+" #btn-detalhes-"+id).addClass("active");
        }

        return;
    }

})