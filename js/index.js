/**
 * Created by Lintean on 2018/5/8.
 */

//define('QUERY_IFMT',1);
//define('QUERY_GD',2);
//define('SHOW_LIST',3);
//define('SHOW_IFMT',4);
//define('SHOW_GD',5);
//define('LOG_IN',6);

function fail(status){
    alert('似乎网络有点问题呢');
}


var cracy = new Vue({
    el:'#cracy',
    data:{
        srIfmt:"学生编号",
        srGd:"学生编号/名字",
        sm:6,
        user:'',
        password:'',
        stdList:[],
        stdIfmt:[],
        grade:'',
        stdNumber:'',
        cuNumber:'',
        gradeNotice:'',
        currentUser:''
    },
    methods:{
        log_in:function(event){
            var data = {
                'user':this.user,
                'password':this.password
            };
            var request =  new XMLHttpRequest();
            request.open("POST","./api/logIn.php",true);
            request.setRequestHeader("Content-Type","application/json");
            request.send(JSON.stringify(data));
            request.onreadystatechange = function(){
                if (request.readyState == 4){
                    if (request.status == 200) {
                        var feedback = JSON.parse(request.responseText);
                        if (feedback['err'] == 0){
                            alert(feedback['msg']);
                            cracy.currentUser = cracy.user;
                            cracy.sm = 3;
                        };
                        if (feedback['err'] == 403){
                            alert(feedback['msg']);
                        }
                    }
                    else return fail(request.status);
                }
            }
        },
        status:function(i){
            return window.st == i;
        },
        query:function(event){
            var status = this.sm;
            if (status == 1){
                var data = {
                    'ID':cracy.stdNumber
                }
                var request =  new XMLHttpRequest();
                request.open("POST","./api/stIfmt.php",true);
            }
            else if (status == 2){
                var data = {
                    'ID':cracy.stdNumber,
                    'name':cracy.stdNumber,
                    'course_id':cracy.cuNumber
                }
                var request =  new XMLHttpRequest();
                request.open("POST","./api/stGd.php",true);
            }
            request.setRequestHeader("Content-Type","application/json");
            request.send(JSON.stringify(data));
            request.onreadystatechange = function(){
                if (request.readyState == 4){
                    if (request.status == 200) {
                        var feedback = JSON.parse(request.responseText);
                        if (feedback['err'] == 0){
                            if (status == 1){
                                cracy.stdIfmt = feedback['data'];
                                cracy.sm = 4;
                            };
                            if (status == 2){
                                cracy.grade = feedback['data'];
                                cracy.gradeNotice = cracy.cuNumber == ''?"该学生的成绩为":"该学生的该课程成绩为";
                                cracy.sm = 5;
                            }

                        } else{
                            alert(feedback['msg']);
                        }
                    }
                    else return fail(request.status);
                }
            }

        },
        getStdList:function(){
            var request =  new XMLHttpRequest();
            request.open("GET","./api/stList.php",true);
            request.send();
            request.onreadystatechange = function(){
                if (request.readyState == 4){
                    if (request.status == 200) {
                        var feedback = JSON.parse(request.responseText);
                        if (feedback['err'] == 0){
                            cracy.stdList = feedback['data'];
                        }else{
                            alert(feedback['msg']);
                        }
                    }
                    else return fail(request.status);
                }
            }
        }
    },
    computed:{
        studentNotice:function(){
            return this.sm == 1 ? this.srIfmt: this.srGd;
        },
    },
    watch:{
        sm:function(value){
            switch (value){
                case 3: cracy.getStdList(); break;
            }
        }
    }
});

document.getElementById('container').style.minHeight = window.innerHeight - 75 + 'px';

function init(){
    var request =  new XMLHttpRequest();
    request.open("GET","./api/user.php",true);
    request.send();
    request.onreadystatechange = function(){
        if (request.readyState == 4){
            if (request.status == 200) {
                var feedback = JSON.parse(request.responseText);
                if (feedback['err'] == 0){
                    cracy.currentUser = feedback['data'];
                    cracy.sm = 3;
                }else{
                    cracy.sm = 6;
                }
            }
            else return fail(request.status);
        }
    }
}

init();
