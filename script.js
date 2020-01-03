window.onload = function () {
    var canvasWidth = 900;
    var canvasHeight = 600;
    var centreX = canvasWidth/2;
    var centreY = canvasHeight/2;
    var blockSize = 30;
    var ctx;
    var delay = 100;
    var snakee;
    var applee;
    var widthInBlocks = canvasWidth/blockSize;
    var heightInBlocks = canvasHeight/blockSize;
    var allowChangeDirect;
    var score;
    var timeout;
    var pause;
    var onPause = false;
    var onGameOver = false;
    var xhr = new XMLHttpRequest();
    var colorRequest = new XMLHttpRequest();
    var keyboardRequest = new XMLHttpRequest();
    var colorSnake;
    var colorHeadSnake;
    var colorApple;
    var isSetColor = false;
    var isSetKeyboard = false;
    var keyUp;
    var keyLeft;
    var keyDown;
    var keyRight;
    var keySpace;
   


    colorRequest.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response;
            if (this.responseText != "empty") {
                response = JSON.parse(this.responseText);   
            }
            else{
                response = "empty";
            }
            setColor(response);
        }
    };
    keyboardRequest.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response;
            if (this.responseText != "empty") {
                response = JSON.parse(this.responseText);   
            }
            else{
                response = "empty";
            }
            setKeyboard(response);
        }
    };


    init();

    function init(){
        colorRequest.open("POST", "post/personnalisation_post.php", true);
        colorRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        colorRequest.send("colorRequest=");
        keyboardRequest.open("POST", "post/personnalisation_post.php", true);
        keyboardRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        keyboardRequest.send("keyboardRequest=");
        var canvas = document.createElement('canvas');
        canvas.width = canvasWidth;
        canvas.height = canvasHeight;
        canvas.style.border ="20px solid gray";
        canvas.style.backgroundColor = "#e4e4e4";
        document.getElementById('snake').appendChild(canvas);
        ctx = canvas.getContext('2d');
        snakee = new Snake([[6,4], [5,4], [4,4],[3,4],[2,4]], "right");
        applee = new Apple([10,10]);
        score = 0;
        refreshCanvas();



        
    }
    
    function refreshCanvas() {    
        snakee.advance(); 
        if (snakee.checkCollision()) {
            gameOver();
            allowRestart = true;
        }
        else{
            if (snakee.IsEatingApple(applee)) {
                score ++;
                snakee.ateApple = true;
                    do {
                        applee.setNewPosition();
                    }
                    while (applee.isOnSnake(snakee));
                

                
            }
            ctx.clearRect(0,0,canvasWidth,canvasHeight);
            drawScore();
            if (isSetColor) {
                snakee.draw();
                applee.draw();
                
            }
            allowChangeDirect = true;


            timeout = setTimeout(refreshCanvas,delay);

        }
    }

    function setColor(color){
        if (color == "empty") {
            colorSnake ="#ff0000";
            colorHeadSnake =  "#01fd00";
            colorApple ="#33cc33";
        }
        else{
            colorSnake = color['colorSelect'];
            colorHeadSnake = color['colorHeadSelect'];
            colorApple = color['colorAppleSelect'];
        }
        isSetColor = true;
    }
    function setKeyboard(key){
        if (key == "empty") {
            isSetKeyboard = false;
        }
        else{
            isSetKeyboard = true;
            keyUp = key['keyUp'];
            keyLeft = key['keyLeft'];
            keyDown = key['keyDown'];
            keyRight = key['keyRight'];
            keySpace = key['keySpace'];
        }
    }


    function pause() {
        if (onPause == true) {
            ctx.save();
            ctx.font = "bold 40px sans-serif";
            ctx.fillStyle = "black";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.strokeStyle ="white";
            ctx.lineWidth =5;
            ctx.strokeText("Appuyer sur Espace pour rejouer",centreX,centreY-120);
            ctx.fillText("Appuyer sur Espace pour rejouer",centreX,centreY-120);
            ctx.restore();
            clearTimeout(timeout);
            onpause = false;
        }
        else{
            refreshCanvas();
   
        }
        
    }
/*
    function restart(){
        snakee = new Snake([[6,4], [5,4], [4,4],[3,4],[2,4]], "right");
        applee = new Apple([10,10]);
        score = 0;
        clearTimeout(timeout);
        refreshCanvas();
        allowRestart = false;

    }*/

    function drawScore(){
        ctx.save();
        ctx.font = "bold 200px sans-serif";
        ctx.fillStyle = "gray";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText(score.toString(),centreX,centreY);
        ctx.restore();

    }

    function gameOver(){
        onGameOver = true;
        ctx.save();
        ctx.font = "bold 70px sans-serif";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.strokeStyle ="white";
        ctx.lineWidth =5;
        ctx.strokeText("Game Over",centreX,centreY-180);
        ctx.fillText("Game Over",centreX,centreY-180);
        ctx.restore();
        clearTimeout(timeout);
        ajaxSend();
        
        
    }
    function ajaxSend() {
        var scoreEncode = encodeURIComponent(score);
        xhr.open("POST", "post/classement_post.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("score="+scoreEncode);
        
    }
   



    function drawBlock(ctx,position) {  
        var x = position[0] * blockSize;
        var y = position[1] * blockSize;
        ctx.fillRect(x , y, blockSize, blockSize);
    }









/* CLASS SNAKE*/






    function Snake(body,direction){
        this.body = body;
        this.direction = direction;
        this.ateApple = false;
        this.draw = function(){
            ctx.save();
            ctx.fillStyle = colorSnake;
            for(var i =0; i < this.body.length; i++){
                if (i==0) {
                    ctx.save();
                    ctx.fillStyle = colorHeadSnake;
                    drawBlock(ctx, this.body[i]);
                    ctx.restore();
                }
                else{

                    drawBlock(ctx, this.body[i]);
                }

            }
            ctx.restore();
        };
        this.advance = function(){
            var nextPosition = this.body[0].slice();
            switch (this.direction) {
                case "left":
                    nextPosition[0] -=1;
                    break;
                case "right":
                    nextPosition[0] +=1;
                    break;
                case "up":
                    nextPosition[1] -=1;
                    break;
                case "down":
                    nextPosition[1] +=1;
                    break;
                default:
                    throw("Invalid Direction");
            
            }
            this.body.unshift(nextPosition); /*Ajouter la nouvelle position */
            if (!this.ateApple) {
                this.body.pop();  /*Suprimmer le dernier élément */
            }else{
                this.ateApple = false;
            }
            
        };
        this.setDirection = function(newDirection){
            var allowedDirections;
            if (allowChangeDirect){
                switch (this.direction) {
                    case "left":
                        allowedDirections = ["up","down"];
                        break;
                    case "right":
                        allowedDirections = ["up","down"];
                        break;
                    case "up":
                        allowedDirections = ["right","left"];
                        break;
                    case "down":
                        allowedDirections = ["right","left"];
                        break;
                    default:
                        throw("Invalid Direction");
                }
                if (allowedDirections.indexOf(newDirection) > -1){
                    this.direction = newDirection;
                    allowChangeDirect = false;
                    
                }
        }
        };
        this.checkCollision = function(){
            var wallCollision = false;
            var snakeCollision = false;
            var head = this.body[0];
            var rest = this.body.slice(1);
            var snakeX = head[0];
            var snakeY = head[1];
            var minX = 0;
            var minY = 0;
            var maxX = widthInBlocks-1;
            var maxY = heightInBlocks-1;
            var isNotBeetweenHorizontalWalls = snakeX < minX || snakeX > maxX;
            var isNotBeetweenVerticalWalls = snakeY < minY || snakeY > maxY;


            if (isNotBeetweenHorizontalWalls || isNotBeetweenVerticalWalls ) {
                wallCollision = true;
            }
            for (var i =0; i < rest.length ; i++){
                if (snakeX == rest[i][0] && snakeY == rest[i][1] ) {
                    snakeCollision = true;
                    
                }
            }
            return wallCollision || snakeCollision;
                
            
        };
        this.IsEatingApple = function(appleEat){
            var head = this.body[0];
            if (head[0] === appleEat.position[0] && head[1] === appleEat.position[1]){
                return true;
            }
            else{
                return false;
            }

        };

    }





/* CLASS APPLE*/


    function Apple(position){
        this.position = position;
        this.draw = function(){
            ctx.save();
            ctx.fillStyle = colorApple;
            ctx.beginPath();
            var radius = blockSize/2;
            var x = this.position[0]*blockSize + radius;
            var y = this.position[1]*blockSize + radius;
            ctx.arc(x,y,radius,0,Math.PI*2, true);
            ctx.fill();

            ctx.restore();

        };
        this.setNewPosition = function () { 
            var newX = Math.round(Math.random() * (widthInBlocks -1));
            var newY = Math.round(Math.random() * (heightInBlocks -1));
            this.position = [newX, newY];
         }
        this.isOnSnake = function(snakeToCheck){
            var isOnSnake = false;
            for (var i =0; i< snakeToCheck.body.length; i++){
                if(this.position[0] == snakeToCheck.body[i][0] && this.position[1] == snakeToCheck.body[i][1] ){
                    isOnSnake = true;
                }
            }
            return isOnSnake;
        };
    }
   
    

/* EVENT KEY DOWN*/


    document.onkeydown = function handleKeyDown(e){
        var key = e.keyCode;
        var newDirection;
        if (isSetKeyboard) {
            switch (key) {
                case parseInt(keyLeft):
                    if (!onPause) {
                        newDirection ="left";
                        
                    }
                    if( e.preventDefault){
                        e.preventDefault();
                        e.stopPropagation();
                      }
                        
                    break; 
                case parseInt(keyUp):
                        if (!onPause) {
                            newDirection ="up";
                        }
                        if( e.preventDefault){
                            e.preventDefault();
                            e.stopPropagation();
                          }
                          break; 
                case parseInt(keyDown):
                        if (!onPause) {
                            newDirection ="down";
                        }
                        if( e.preventDefault){
                            e.preventDefault();
                            e.stopPropagation();
                          }
                          break;
                case parseInt(keyRight):
                        if (!onPause) {
                            newDirection ="right";
                        }
                        if( e.preventDefault){
                            e.preventDefault();
                            e.stopPropagation();
                          }
                          break;
                case parseInt(keySpace):
                    if (onGameOver == false) {
                        if (onPause == false) {
                            onPause = true;
                        }
                        else{
                            onPause = false;
                        }
                        pause(); 
                    }else{
                        document.location.reload(true);
                    }
                    return
                default:
                    return;
                }
        }else{

            switch (key) {
                case 37:
                case 81:
                    if (!onPause) {
                        newDirection ="left";
                        
                    }
                        
                    break; 
                case 38:
                        if (!onPause) {
                            newDirection ="up";
                        }
                        if( e.preventDefault){
                            e.preventDefault();
                            e.stopPropagation();
                          }
                          break; 
                case 90:
                        if (!onPause) {
                            newDirection ="up";
                        }
                    break; 
                case 39:
                case 68:
                        if (!onPause) {
                            newDirection ="right";
                            
                        }
                    break; 
                case 40:
                        if (!onPause) {
                            newDirection ="down";
                        }
                        if( e.preventDefault){
                            e.preventDefault();
                            e.stopPropagation();
                          }
                          break;
                case 83:
                        if (!onPause) {
                            newDirection ="down";
                        }                       
                   break;
                case 32:
                    if (onGameOver == false) {
                        if (onPause == false) {
                            onPause = true;
                        }
                        else{
                            onPause = false;
                        }
                        pause();     
                    }else{
                        document.location.reload(true);
                    }
                    return
                default:
                    return;
                }
        }

                
                      
                timer =1;
                snakee.setDirection(newDirection);
        }
    

    
}