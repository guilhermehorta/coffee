document.addEventListener("DOMContentLoaded", function(event)
{
	// Define the dimensions of the map   
    const nhl = 5; // number of horizontal lines
    const nvl = 6; // number of vertical lines
    const w = 450; // width of the svg element
    const h = 450; // height of the svg element
    const pad = 30; // padding aroun the map
    const lineWidth = 1; // the width of the lines
    const lineOpacity = .3;
    const lineColor = "black"; // the color of the lines
    const boxWidth = (w - 2 * pad) / (nvl - 1);
    const boxHeight = (h - 2 * pad) / (nhl - 1);

    let xScale = d3.scaleLinear()
    	.domain([0, nvl - 1])
    	.range([0, w - 2 * pad]);

    let yScale = d3.scaleLinear()
    	.domain([0, nhl - 1])
    	.range([ h - 2 * pad, 0]);


    const fullmap = d3.select("#fullmap") // select the 'fullmap' element on the page
		//.attr("style", "outline: thin solid red")
		.attr("width", w)      // set the width
		.attr("height", h);    // set the height

    const grid = fullmap.append('g')
    	.attr('transform', 'translate(' + pad + ',' + (h - pad) + ')');

	// Append the lines to the svg
	for (let x = 0; x < nvl; x++)
	{
		createLine(grid, xScale(x), yScale(0), xScale(x), yScale(nhl - 1), lineWidth, lineColor, lineOpacity);
	}
	for (let y = 0; y < nhl; y++)
	{
		createLine(grid, xScale(0), yScale(y), xScale(nvl - 1), yScale(y), lineWidth, lineColor, lineOpacity);	
	}


	// Now the clients
	const clientPos = fullmap.append('g')
		.attr('transform', 'translate(' + (pad - (0.98 * boxWidth)) + ',' + (pad + (0.95*boxHeight)) + ')');

	clientPos.selectAll(".client")
		.data(d3.entries(clients))
		.enter()
		.append("g")
		.attr("class", "client")
		.attr("transform", function(d){
			xVal = 11 * d.value.order + xScale(d.value.xPos);
			yVal = -14 * d.value.order + yScale(d.value.yPos);
			return "translate(" + xVal + "," +  yVal + ")";
//			return "translate(" + 11 * d.value.order + xScale(d.value.xPos) + "," +  -14 * d.value.order + yScale(d.value.yPos) + ")";
		})
		.append("path")
			.attr("d", d3.symbol().type(d3.symbolStar).size("100")())
			//.attr("fill", "#00aa88");
			.attr("fill", function(d){
				if(d.value.lost) return "red";
				return "green";
			});

	clientPos.selectAll(".legend")
		.data(d3.entries(clients))
		.enter()
		.append("text")
		.attr("class", "legend")
		.text(function(d){
			return d.key;
		})
		.attr("x", function(d){
			return 8 + 11 * d.value.order + xScale(d.value.xPos);
		})
		.attr("y", function(d){
			return -14 * d.value.order + yScale(d.value.yPos) + 6;
		})
		.attr("font-size", "11px")
		.attr("fill", "black");

	// Now the factories
	const factoryPos = fullmap.append('g')
		.attr('transform', 'translate(' + (pad - (boxWidth/2)) + ',' + (pad + (boxHeight/3)) + ')');

	factoryPos.selectAll(".factory")
		.data(d3.entries(factories))
		.enter()
		.append("g")
		.attr("class", "factory")
		.attr("transform", function(d){
			return "translate(" + xScale(d.value.xPos) + "," + yScale(d.value.yPos) + ")";
		})
		.append("path")
			.attr("d", function(d){
				if(d.value.own) return d3.symbol().type(d3.symbolCircle).size("400")();
				return d3.symbol().type(d3.symbolCircle).size("200")();
			})
			.attr("fill", function(d){
				if(d.value.own) return "green";
				return "purple";
			});

	factoryPos.selectAll(".legend")
		.data(d3.entries(factories))
		.enter()
		.append("text")
		.attr("class", "legend")
		.text(function(d){
			return d.key;
		})
		.attr("x", function(d){
			return xScale(d.value.xPos);
		})
		.attr("y", function(d){
			return yScale(d.value.yPos);
		})
		.attr("font-size", "11px")
		.attr("fill", "black");


	// Now the suppliers
	const supplierPos = fullmap.append('g')
		.attr('transform', 'translate(' + (pad - (boxWidth/2)) + ',' + (pad + (boxHeight/2)) + ')');

	supplierPos.selectAll(".supplier")
		.data(d3.entries(suppliers))
		.enter()
		.append("g")
		.attr("class", "supplier")
		.attr("transform", function(d){
			return "translate(" + xScale(d.value.xPos) + "," + yScale(d.value.yPos) + ")";
		})
		.append("path")
			.attr("d", function(d){
				if(d.key == "recyclingPlant") return d3.symbol().type(d3.symbolWye).size("200")();
				if(d.key == "water") return d3.symbol().type(d3.symbolDiamond).size("200")();
				if(d.key == "wholesaler") return d3.symbol().type(d3.symbolSquare).size("200")();
			})
			.attr("fill", "#00aa88");

	supplierPos.selectAll(".legend")
		.data(d3.entries(suppliers))
		.enter()
		.append("text")
		.attr("class", "legend")
		.text(function(d){
			// return d.key; // mantém o texto em inglês
			// vou traduzir o texto à mão
			if (d.key == 'recyclingPlant') return 'reciclagem';
			if (d.key == 'water') return 'água';
			if (d.key == 'wholesaler') return 'açucar, café e garrafas';
		})
		.attr("x", function(d){
			return xScale(d.value.xPos);
		})
		.attr("y", function(d){
			return yScale(d.value.yPos);
		})
		.attr("font-size", "11px")
		.attr("fill", "black");


	function createLine(target, x1, y1, x2, y2, width, color, opacity)
	{
		target.append('line')
			.attr("x1", x1)
			.attr("y1", -y1)
			.attr("x2", x2)
			.attr("y2", -y2)
			.attr("stroke-width", width)
			.attr("stroke-opacity", opacity)
			.attr("stroke", color);
	}

})




/*
LBH9140866
157915
MILLENIUM


*/