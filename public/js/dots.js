/*

Inspiration: http://bl.ocks.org/mbostock/d7bf3bd67d00ed79695b

Amazingly, even with high numbers of dots and high levels of smoothing,
finding the ideal position for a new dot never takes takes longer than
a millisecond. I was sure the brute-force approach would lag and I'd 
have to implement a quadtree or something.

*/

(function (canvas) {
    'use strict';

    var ctx = canvas.getContext('2d'),
        cw = canvas.width,
        ch = canvas.height,
        twoPi = Math.PI * 2,
        dotRadius = 3,
        dotColor = '#0AA594',
        dotCount = 1,
        samples = 50, // candidate dots attempted, higher is better
        placedDots = [], // a dot is represented as [x, y]

        initialize = function () {
            var dotsDrawn = 0,
                interval = setInterval(function () {
                    // var elapsed = (new Date()).getTime();
                    placeNewDot();
                    // elapsed = (new Date()).getTime() - elapsed;
                    dotsDrawn++;
                    // console.log('Dot #' + dotsDrawn + ' drawn in ' + elapsed + 'ms.');
                    if (dotsDrawn === dotCount) clearInterval(interval);
                }, 20);
        },

        generateRandomPosition = function () {
            return [
            Math.round(Math.random() * cw),
            Math.round(Math.random() * ch)];
        },

        getDistanceToNearestDot = function (dot) {
            var shortest;
            for (var i = placedDots.length - 1; i >= 0; i--) {
                var distance = getDistance(placedDots[i], dot);
                if (!shortest || distance < shortest) shortest = distance;
            }
            return shortest;
        },

        getDistance = function (dot1, dot2) {
            var xDistance = Math.abs(dot1[0] - dot2[0]),
                yDistance = Math.abs(dot1[1] - dot2[1]),
                distance = Math.sqrt(Math.pow(xDistance, 2) + Math.pow(yDistance, 2));
            return Math.floor(distance);
        },

        generateBestDot = function () {
            var bestDot, bestDotDistance;
            for (var i = 0; i < samples; i++) {
                var candidateDot = generateRandomPosition(),
                    distance;
                if (!placedDots.length) return candidateDot;
                distance = getDistanceToNearestDot(candidateDot);
                if (!bestDot || distance > bestDotDistance) {
                    bestDot = candidateDot;
                    bestDotDistance = distance;
                }
            }
            return bestDot;
        },

        placeNewDot = function () {
            var dot = generateBestDot();
            placedDots.push(dot);
            drawDot(dot);
        },

        drawDot = function (dot) {
            ctx.fillStyle = dotColor;
            ctx.beginPath();
            ctx.arc(dot[0], dot[1], dotRadius, 0, twoPi);
            ctx.fill();
        };

    initialize();
    alert(placedDots);

}(document.getElementById('canvas')));
