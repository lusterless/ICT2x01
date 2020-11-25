/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function fileValidation(){
    var fileInput = document.getElementById("formativeFile");
    var filePath = fileInput.value;
    var allowedExtensions =  /(\.xlsx|\.xls)$/i;

    if (!allowedExtensions.exec(filePath)){
        alert("Please insert a valid file type \n\n .xls, .xlsx");
        fileInput.value = "";
        document.getElementById('arrayFeedback').value = "";
        return false;
    }else{
        if(fileInput.files && fileInput.files[0]){
                var reader = new FileReader();
                reader.onload=function(e){
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, {type: 'array'});
                    var firstSheet = workbook.Sheets[workbook.SheetNames[0]];                       
                    var result = XLSX.utils.sheet_to_json(firstSheet, { header: 1, defval: null});
                    document.getElementById('arrayFeedback').value = JSON.stringify(result);
                }
                reader.readAsArrayBuffer(fileInput.files[0]);
        }
    }
}
