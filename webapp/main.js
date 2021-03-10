"use strict"

//let urlCourse = 'http://localhost/moment5dt173g/Api/courses.php';
let urlCourse = 'http://studenter.miun.se/~mali1910/dt173g/moment5/api/courses.php';

let courseEl = document.getElementById("course");

let updateCourseEl=document.getElementById("updateCourse-form");


let addbtn= document.getElementById("addCourse");
let codeInput= document.getElementById("code"); 
let nameInput= document.getElementById("name"); 
let progressionInput= document.getElementById("progression");
let syllabusInput= document.getElementById("syllabus");




//eventlistener
window.addEventListener('load', getCourse);

addbtn.addEventListener('click',addCourse);


//functions for work objects
function getCourse(){
    courseEl.innerHTML='';
    fetch(urlCourse)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        data.courselist.forEach(course =>{
            courseEl.innerHTML +=
            `<tr>
            <td>${course.code}</td>
            <td>${course.name}</td>
            <td>${course.progression}</td>
            <td>${course.syllabus}</td>
            <td><a onclick="getCoursebyId(${course.id})"class="btn btn-success">Uppdatera</a></td>
            <td><a onclick="deleteCourse(${course.id})" class="btn btn-danger">Radera</a></td>
          </tr>`;
           
        })
    })
}
function getCoursebyId(id){
    fetch(`${urlCourse}?id=${id}`)
    .then(response => response.json())
    //.then(updateWorkEl.style.display = 'block')
    .then(course => {   
  
            updateCourseEl.innerHTML +=
            `<form class="form-group" method="get">
            <h3>Uppdatera kurs</h3> <br>
            <label for="code">Kurskod</label>
            <input type="text" class="form-control"id="updcode" value="${course.code}"> <br>
            <label for="title">Namn</label>
            <input type="text" class="form-control"id="updname" value="${course.name}"> <br>
            <label for="progression">Progression</label>
            <input type="text" class="form-control"  id="updprogression" value="${course.progression}"> <br>
            <label for="stop">syllabus</label>
            <input type="text" class="form-control" id="updsyllabus" value="${course.syllabus}"> <br>
            <input type="submit" id="updateBtn" class="btn btn-success" onClick="updateCourse(${course.id})" value="Uppdatera"> <br> 
            <input type="submit" class="btn btn-danger" onClick="closeDiv()" value="Avbryt">     
            </form>`     
    })
}


function updateCourse(id){
    let newCode = document.getElementById('updcode');
    let newName = document.getElementById('updname');
    let newProgression = document.getElementById('updprogression');
    let newSyllabus = document.getElementById('updsyllabus');

    newCode = newCode.value;
    newName = newName.value;
    newProgression = newProgression.value;
    newSyllabus = newSyllabus.value;

    let course = {'id':id, 'code':newCode, 'name':newName, 'progression':newProgression, 'syllabus': newSyllabus};

    fetch(`${urlCourse}?id=${id}`,{
        method:'PUT',
        body: JSON.stringify(course)
    })
    .then(response => response.json())
    .then(data =>{
        getCourse();
    })
    .catch(error=> {
        console.log('Error: ',error);
    })

}
function deleteCourse(id){
    fetch(`${urlCourse}?id=${id}`, {
        method:'DELETE',
    })
    .then(response=>response.json())
    .then(data=>{
        getCourse();
    })
    .catch(error =>{
        console.log("Error:", error);
    })
}



function addCourse(){
    let code = codeInput.value;
    let name = nameInput.value;
    let progression = progressionInput.value;
    let syllabus = syllabusInput.value;

    let course = {'code':code, 'name':name, 'progression':progression, 'syllabus':syllabus};
    fetch(urlCourse, {
        method:'POST',
        body:JSON.stringify(course),
    })
    .then(response=>response.json())
    .then(data=>{
        getCourse();
    })
    .catch(error =>{
        console.log("Error:", error);
    });
}