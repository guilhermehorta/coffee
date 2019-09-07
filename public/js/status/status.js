document.addEventListener("DOMContentLoaded", function(event)
{
    
    // Eliminar rota
    $(".delete-route").click(function(){deleteRoute($(this));});

    // Desenho dos depósitos
    depositos();

    
    if (phase == 'outbound')
    {
        // Drag and drop para rotas
        new Sortable(rota_0,{
            group: {
                name: 'shared',
                pull: 'clone',
                put: false
            },
            animation: 150,
            sort: false,
            draggable: '.route-item',
            onEnd: function(){
                boxesToText();
                transportCost();
            }
        });    
/*        new Sortable(rota_1,{
            group: {
                name: 'shared'
            },
            animation: 150,
            draggable: '.route-item',
            removeOnSpill: true,
            onEnd: function(){
                boxesToText();
                transportCost();
            }
        }); */

        // Criar rotas iniciais
        textToBoxes();

        // Cálculo inicial custos transportes
        transportCost();
    }


    // Styling e funcionamento das entradas numéricas
    // Este código btnUp e btnDown não está à prova de bala. REVER
    jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.input-number-wrapper input');
    jQuery('.input-number-wrapper').each(function() {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.quantity-up'),
        btnDown = spinner.find('.quantity-down'),
        min = input.attr('min'),
        max = input.attr('max'),
        step = parseFloat(input.attr('step')),
        decimals = parseInt(input.attr('decimals'));
        

      btnUp.click(function() {
        if (input.val() == '')
        {
            var oldValue = 0;
        }
        else
        {
            var oldValue = parseFloat(input.val());
        }
        if (oldValue >= max) {
          var newVal = oldValue;
        } else {
          var newVal = (oldValue + step).toFixed(decimals);
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
        var oldValue = parseFloat(input.val());
        if (oldValue <= min) {
          var newVal = oldValue;
        } else {
          var newVal = (oldValue - step).toFixed(decimals);
        }
        if (newVal == 0) newVal = '';
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });

    function boxesToText()
    {
        var str = "";
        var substr = "";
        $(".route-group").each(function(index) {
            substr = "";
            $(this).children(".route-item").each(function(index){                
                substr += $(this).html();
                substr += ",";
            });
            if (substr)
            {
                substr = substr.slice(0,-1);
                substr += ";";
            } 
            str += substr;
        });
        str = str.slice(0,-1);
        $('#routes').val(str);
    }

    function textToBoxes()
    {
        var str = $('#routes').val();

        if (str == "")
        {
            buildNewRoute();
            return;
        }

        let routesArray = str.split(";");
        routesArray.forEach(function(value, index, array){
            routeNumber = buildNewRoute();
            clientsArray = value.split(",");
            clientsArray.forEach(function(value, index, array){
                addNewItem(routeNumber, value);
            });  
        });
        transportCost();
        return;
        
    }

    function transportCost()
    {
        //
        // Estimar custos de transporte
        //

        // The game_id and role_id were already JSONed into js
        // Get the routesString
        var routesString = $('#routes').val();

        // Make the request
        axios.get('/api/getTransportCost', {
            params: {
                game : game_id,
                role : role_id,
                routesString : routesString
            }
        })
            .then(function (response) {
                // handle success
                //console.log(response.data);
                //console.log(response.status);
                if (response.data.success == true)
                {
                    $('#transport-cost-estimate').html((response.data.cost).toFixed(2));
                }
                else
                {
                    $('#transport-cost-estimate').html("erro");
                }
            })
            .catch(function (error) {
                if (error.request)
                {
                    console.log(error.request);
                }
                else
                {
                    console.log('Error', error.message);
                }
                console.log(error.config);                
            });
        return;
    }

    function buildNewRoute()
    {
        //
        // Build a new route
        //

        // Get the route number
        let maxNumber = 0;
        $('.route-group').each(function(index) {
            if($(this).data("number") > maxNumber) maxNumber = $(this).data("number");
        });
        let nextNumber = maxNumber + 1;

        
        // Do the html stuff
        var str = "";

        str += '<div id="rota_'+nextNumber+'" class="grid-table route-group" data-number="'+nextNumber+'">';
        str += '<div class="top-row"> Rota '+nextNumber+'</div>';

        if (nextNumber == 1)
        {
            str += '<div class="top-row" id="new-route">';
            str += '<input type="button" value="NOVA">';
        }
        else
        {
            str += '<div class="top-row delete-route" data-number="'+nextNumber+'">';
            str += '<input type="button" value="ELIM.">';
        }
        
        str += '</div';
        str += '</div';

        $("#rotas").append(str);

        if (nextNumber == 1)
        {
            // Ativar o novo botão new-route
            $("#new-route").click(function(){buildNewRoute();});
        }
        else
        {
            // Ativar o novo botão delete
            $(".delete-route").off("click");
            $(".delete-route").click(function(){deleteRoute($(this));});
        }

        // make the new div sortable
        var el = document.getElementById('rota_'+nextNumber);
        new Sortable(el,{
            group: {
                name: 'shared'
            },
            animation: 150,
            draggable: '.route-item',
            removeOnSpill: true,
            onEnd: function(){
                boxesToText();
                transportCost();}    
        });

        return nextNumber;

    }

    function addNewItem(route, client)
    {
        //
        // Add a new client to a route
        //

        // Do the html stuff
        var str = "";
        str += '<div class="top-row route-item">'+client+'</div>';
        $("#rota_"+route).append(str);

        return;
    }


    function deleteRoute(obj)
    {
        //
        // Delete a route
        //

        // Get the route number
        let number = obj.data("number");
        let toDelete = document.getElementById('rota_'+number);
        toDelete.parentNode.removeChild(toDelete);
        boxesToText();
        transportCost();

        return;
    }

    
    function depositos(){
        let u = d3.select("#deposits")
            .selectAll(".tank")
            .data(d3.entries(tanks));
   
        u.each(function(value){
            var config = liquidFillGaugeDefaultSettings();
            config.maxValue = rules.tankMaxCapacity;
            config.displayPercent = false;
            if(value.value.contains == 'water')
            {
                config.maxValue = rules.tankMaxCapacity;
                config.circleColor = "#178BCA";
            } 
            if(value.value.contains == 'freshSoda')
            {
                config.maxValue = rules.tankMaxCapacity / material.water.amount;
                config.circleColor = "#91f486";
                config.waveColor = "#805206";
            }
            if(value.value.contains == 'warmSoda')
            {
                config.maxValue = rules.tankMaxCapacity / material.water.amount;
                config.circleColor = "#FF8C00";
                config.waveColor = "#805206";
            }
            if(value.value.contains == 'damagedSoda')
            {
                config.maxValue = rules.tankMaxCapacity / material.water.amount;
                config.circleColor = "#FF0000";
                config.waveColor = "#000000";
            }
            return loadLiquidFillGauge("fillgauge"+value.key, value.value.quantity, config);
        });

    }


})