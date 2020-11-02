var app = new Vue({
  el: "#app",
  data: {
    errors: [],
    step: 1,
    module: null,
    startdate: null,
    enddate: null,
    categories: ["CA", "Exam", "CT"],
    totalWeightage: 100,
    assessments: [
      {
        category: "CA",
        weightage: 100,
        subAssessments: [
          {
            name: "",
            weightage: 0,
          },
        ],
      },
    ],
  },
  methods: {
    nextStep() {
      this.errors = [];
      if (this.step >= 1 && !this.module) {
        this.addError("Module name required!");
      } else if (this.step >=1 && !this.startdate) {
        this.addError("Start date required!")
      } else if (this.step >=1 && !this.enddate) {
        this.addError("End date required!")
      } else if (this.step >=1 && this.enddate < this.startdate) {
        this.addError("End date cannot be earlier than start date!")
      } else if (this.step >= 2 && this.assessments.length > 0) {
        this.checkAssessments();
      }
      if (this.errors.length === 0) {
        this.$set(this, "step", this.step + 1);
      }
    },
    prevStep() {
      if (this.step > 1) {
        this.errors = [];
        this.$set(this, "step", this.step - 1);
      }
    },
    addError(newError) {
      this.errors.filter((err) => err === newError).length === 0
        ? this.errors.push(newError)
        : null;
    },
    addAssessment() {
      if (this.assessments.length < this.categories.length) {
        this.assessments.push({
          category: "",
          weightage: 0,
          subAssessments: [{ name: "", weightage: 0 }],
        });
      }
    },
    removeAssessment(index) {
      if (this.assessments.length > 1) {
        this.assessments.splice(index, 1);
      }
    },
    addModule: function(){
        if(this.module !== '' && this.startdate !== '' && this.enddate !== ''){
            axios.post('ajaxfile.php', {
            request: 1,
            module: this.module,
            startdate: this.startdate,
            enddate: this.enddate,
            assessment: this.assessments
            })
        for(i=0;i < this.assessments.length;i++){
            axios.post('ajaxfile.php', {
            request: 2,
            assessmentid: i + 1,
            category: this.assessments[i].category,
            assessmentweightage: this.assessments[i].weightage
            

            })
        }
        for(i=0;i < this.assessments.length;i++){
            for(j=0;j<this.assessments[i].subAssessments.length;j++){
                    axios.post('ajaxfile.php', {
                    request: 3,
                    assessmentid: i + 1,
                    subassessmentname: this.assessments[i].subAssessments[j].name,
                    subassessmentweightage: this.assessments[i].subAssessments[j].weightage        
                }) 
            }
        }
        } else{
            alert('Fill all fields.');
        }
   
    },
    addSubAssessment(index, subIndex) {
      this.assessments[index].subAssessments.splice(subIndex + 1, 0, {
        name: "",
        weightage: 0,
      });
    },
    removeSubAssessment(index, subIndex) {
      if (this.assessments[index].subAssessments.length > 1) {
        this.assessments[index].subAssessments.splice(subIndex, 1);
      }
    },
    checkAssessments() {
      let selectedCategories = new Set();
      let currentTotalWeightage = 0;
      this.assessments.forEach((assessment) => {
        // Check assessment categories
        !!assessment.category
          ? selectedCategories.add(assessment.category)
          : this.addError("Please enter a category for each assessment");
        !!assessment.weightage
          ? (currentTotalWeightage += eval(assessment.weightage))
          : this.addError("Please enter a weightage for each assessment");
        // Check subassessments
        let totalWeightage = 0;
        assessment.subAssessments.forEach((sub) => {
          // Check subassessment names
          !!sub.name
            ? null
            : this.addError("Please enter a name for each subassessment");
          // Check subassessment weightages
          totalWeightage += eval(sub.weightage);
        });
        // Check subassessment weightages
        if (totalWeightage !== eval(assessment.weightage)) {
          this.addError(
            "Total subassessment weightage should add up to assessment weightage"
          );
        }
      });
      // Check for unique assessment categories
      if (selectedCategories.size !== this.assessments.length) {
        this.addError("Please choose unique categories for all assessments");
      }
      //  Check assessment weightages
      if (currentTotalWeightage !== eval(this.totalWeightage)) {
        this.addError(
          `The total weightage for all assessments must equal to ${this.totalWeightage}`
        );
      }
    },
    chooseFiles() {
        document.getElementById("fileUpload").click()
    },
    submit() {},
  },
});
  $(document).ready(function(){
    $('#submit-file').on("click",function(e){
		e.preventDefault();
		$('#files').parse({
			config: {
				delimiter: "auto",
				complete: displayHTMLTable,
			},
			before: function(file, inputElem)
			{
				//console.log("Parsing file...", file);
			},
			error: function(err, file)
			{
				//console.log("ERROR:", err, file);
			},
			complete: function()
			{
				//console.log("Done with all files");
			}
		});
    });
	
	function displayHTMLTable(results){
		var table = "<table class='table'>";
		var data = results.data;
		 
		for(i=0;i<data.length;i++){
			table+= "<tr>";
			var row = data[i];
			var cells = row.join(",").split(",");
			 
			for(j=0;j<cells.length;j++){
				table+= "<td>";
				table+= cells[j];
				table+= "</th>";
			}
			table+= "</tr>";
		}
		table+= "</table>";
		$("#parsed_csv_list").html(table);
	}
  });
  
  function loadFile(o)
{
    var fr = new FileReader();
    fr.onload = function(e)
        {
            showDataFile(e, o);
        };
    fr.readAsText(o.files[0]);
}

function showDataFile(e, o)
{ 
  var getCSVData = e.target.result;
  var rows = getCSVData.split("\n");
  var html = '<table border="1">';
  rows.forEach((data, index) => {
    html += "<tr>";
    var value = data.split(",");

    html += "<td>" + value[0] + "</td>";
    html += "<td>" + value[1] + "</td>";
    html += "<td>" + value[2] + "</td>";

    html += "</tr>";
  });
  html += '</table>';
  document.getElementById("data").innerHTML = html;
  document.getElementById("data").style.color="blue";
}