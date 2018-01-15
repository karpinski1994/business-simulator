
//overall budget module
var gameController = (function(){
    
    var Qualification = function(id, name, value, description, cost) {
        this.id = id;
        this.type = 'act';
        this.id = this.id +'-'+ this.type;
        this.name = name;
        this.value = value;
        this.description = description;
        this.cost = cost;
    };
    var Investment = function(id, name, value, description, cost) {
        this.id = id;
        this.type = 'pas';
        this.id = this.id +'-'+ this.type;
        this.name = name;
        this.value = value;
        this.description = description;
        this.cost = cost;
    };
    
    
    /*AJAX */

    var url = 'data.php';
    var method = 'GET';
    var asynch = false;
    /*STH TRY*/
    function loadJSON(url) {
        var request = new XMLHttpRequest();
        request.open(method, url, asynch);
        request.send();

        if (request.readyState === 4 && request.status === 200)
            console.log('receiving data: ' + request.responseText)
            return request.responseText;
    }

    // An example using ipify api (An IP Address API)
    var json = loadJSON(url);
    var db_data = JSON.parse(json);
    
    
    /*AJAX END*/
    var data = {
            act : parseInt(db_data.active_income),
            pas : parseInt(db_data.passive_income),
            total : parseInt(db_data.total_budget),
            isPasSet : false,
        incomeData: {
            quals : [

                new Qualification(0,'Time Management', 1, 'Grows up your active income by 1 for every click', 25),

                new Qualification(1,'Sales Course', 3, 'Grows up your active income by 3 for every click', 50),
                
                new Qualification(2,'Marketing classes', 20, 'Grows up your active income by 20 for every click', 200)
            ],

            invs : [
                new Investment(0,'Manual Labourer', 1, 'Gains your passive income by 1 every 2 seconds', 1000),

                new Investment(1,'Marketing classes', 3, 'Gains your passive income by 3 every 2 seconds', 2000),
                
                new Investment(1,'Team management', 7, 'Gains your passive income by 7 every 2 seconds', 4000)
            ]
        }
    };
    // THIS PART OF CODE NEEDS TO BE IMPROVED
    var takeTypeOfId = function(id) {
        var splittedId, type;
        id = id.toString();
        splittedId = id.split('-');
        type = splittedId[splittedId.length -1];
        return type;
        
    }
    
    var takeNoOfId = function(id) {
        var splittedId, type;
        id = id.toString();
        splittedId = id.split('-');
        id = splittedId[splittedId.length -2];
        return id;
        
    }
    // THOSE PARTS OF CODE NEEDS TO BE IMPROVED
    
    
    return {
        
        getBudgetData : function() {
            return data;
        },
        
        
        buy : function(transactionData) {
            var id, type, cost, message;
            id = takeNoOfId(transactionData);
            type = takeTypeOfId(transactionData);
            
            
            if(type === 'act'){
                cost = data.incomeData.quals[id].cost;
                message = 'You dont have enough money!\nCost : ' + cost+'$';
               if(data.total >= cost){
                   data.total -= cost;
                   data.act += data.incomeData.quals[id].value;
               }else {
                   alert(message);
               }
                

            }else if(type === 'pas'){
                cost = data.incomeData.invs[id].cost;
                message = 'You dont have enough money!\nCost : ' + cost +'$';
                if(data.total >= cost){
                   data.total -= cost;
                    data.pas += data.incomeData.invs[id].value;
               }else {
                   alert(message);
               }
            }
        },
        
    };
    
    
})();


/*-------------------------------------------------------------------------------------------*/



//UI module
var UIController = (function() {
    
    var DOMStrings = {
        
        workBtn : '#work-btn',
        curBudget : '#current-budget',
        curActInc: '#current-active-income',
        curPasInc: '#current-passive-income',
        //qual data
        actIncContainer : '.quals-container',
        pasIncContainer : '.invs-container',
        incomeContainer: '#income-container',
        logOutBtn : '#logout-btn'
        
    };
    var displayIncomeSource = function (obj, type) {
            var newHtml;
                newHtml = '<div class="card"><div class="card-block text-center"><div class="btn btn-s btn-info" id="'+obj.id+'">'+obj.name+'</div><p class="text-center">Cost: '+obj.cost+'</p><div class="jumbotron jumbotron-fluid"><p class="text-center">'+obj.description+'</p></div></div></div>';
            if ( obj.type === 'act'){
                document.querySelector(DOMStrings.actIncContainer).insertAdjacentHTML('beforeend', newHtml);
            }else {
                document.querySelector(DOMStrings.pasIncContainer).insertAdjacentHTML('beforeend', newHtml);
            }  
        };
    // some code
    return {
        
        getDOMStrings : function() {
            return DOMStrings;
        },
        displayBudget : function(budget){
            document.querySelector(DOMStrings.curBudget).textContent = budget;
        },
        displayActInc : function (act) {
            document.querySelector(DOMStrings.curActInc).textContent = act;
        },
        displayPasInc : function (pas) {
            document.querySelector(DOMStrings.curPasInc).textContent = pas;
        },
        displayAllIncomeSources: function (arr){
            for(var i = 0; i < arr.length; i++){
                displayIncomeSource(arr[i]);
            }
        }
        
    }
    
    
})();




/*-------------------------------------------------------------------------------------------*/



//app/controller module
var controller = (function(budgetCtrl, UICtrl) {
    
    var setupEventListeners = function() {
        
        // WORK active btns
        document.querySelector(DOM.workBtn).addEventListener('click', work);
        // space/enter trigger
        document.addEventListener('keypress', function(e){
            if(e.keyCode === 32 || e.which === 32 || e.keyCode === 13 || e.which === 13) {
                e.preventDefault();
                work();
            }
        
        });
        // BUYING btns
        document.querySelector(DOM.incomeContainer).addEventListener('click', buyTrigger);
        document.querySelector(DOM.logOutBtn).addEventListener('click', updateDBData);
        
    };
    
    //dom strings
    var DOM = UICtrl.getDOMStrings();
    
    
    /*TO CHANGE*/
    var budgetData = gameController.getBudgetData();
    
    
   var total_income = budgetData.total;
   var active_income = budgetData.act;
   var passive_income = budgetData.pas;
    console.log('total '+  total_income);
    console.log('active '+  active_income);
    console.log('passive '+  passive_income);
    var updateDBData = function() {
        /*function callPHP(params) {
        var httpc = new XMLHttpRequest(); // simplified for clarity
        var url = "JSON_Handler.php";
        httpc.open("POST", url, true); // sending as POST

        httpc.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            // POST request MUST have a Content-Length header (as per HTTP/1.1)

        httpc.onreadystatechange = function() { //Call a function when the state changes.
        if(httpc.readyState == 4 && httpc.status == 200) { // complete and no errors
            
            console.log(httpc.responseText); // some processing here, or whatever you want to do with the response
            }
            }
            httpc.send(params);
        }
    
        callPHP(budgetDataJSON);*/
        
        function myJavascriptFunction() { 
          var jsonBudgetData = JSON.stringify(budgetData);
          window.location.href = "JSON_Handler.php?budget=" + jsonBudgetData; 
        }
        myJavascriptFunction();
    };
     /*TO CHANGE*/
    
    
    
    
    
    
    
    var updateData = function(amount) {

        UICtrl.displayBudget(budgetData.total);
    };
     var work = function(){
        budgetData.total += budgetData.act;
         console.log(budgetData);
         updateData();
    };
       function gainBudget(){
        budgetData.total += budgetData.pas;
           console.log(budgetData);
           updateData();
    }
    
    //THIS PART NEEDS TO BE INPROVED!
    function Interval(fn, time) {
        var timer = false;
        this.start = function () {
            if (!this.isRunning())
                timer = setInterval(fn, time);
        };
        this.stop = function () {
            clearInterval(timer);
            timer = false;
        };
        this.isRunning = function () {
            return timer !== false;
        };
    }

    var i = new Interval(gainBudget, 2000);
    
        //THIS 


    //poniższa funkcja odpalana jest za pomocą clicknięcia.
    //dokonujemy za jej pomocą zakupu inwestycji
    //zakup kazdej kolejnej inwestycji zwieksza budget.data.pas o 1
    var buyTrigger = function(e){ // 
        budgetCtrl.buy(e.target.id);
        updateData();
        UICtrl.displayActInc(budgetData.act);
        UICtrl.displayPasInc(budgetData.pas);
        //jeżeli dochód pasywny - budgetData.pas  jest wiekszy od zera
        // wlacza sie interwal dochodu pasywnego
        startPasInc();
        
    }
    var startPasInc = function(){
        var pasCheck = false;
        if(budgetData.pas > 0){
            if(pasCheck === false){
                i.start();
                pasCheck = true;
            }else{
                return;
            }
        }
    }
    
    
    // THIS PART OF CODE NEEDS TO BE IMPROVED 
 
    return {
        init : function(){
                console.log('Init confirmed.');
   
                startPasInc();
                updateData();
                //displaying setup
                 UICtrl.displayActInc(budgetData.act);
                 UICtrl.displayPasInc(budgetData.pas);

                //displaying income data
                 UICtrl.displayAllIncomeSources(budgetData.incomeData.quals)
                 UICtrl.displayAllIncomeSources(budgetData.incomeData.invs)
                 setupEventListeners();
            },
        
        };
        
    
})(gameController, UIController);


controller.init();
