
var app = new Vue({
    el: "#app",
    data: {
        errors: [],
        step: 1,
        module: null,
        currentdate:null,
        startdate: null,
        enddate: null,
        fileName: "",
        students: [],
        moduleno: null,
        database: "",
        csvfile: null,
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
        async nextStep() {
            this.errors = [];
            var today = new Date();
            var date = today.toJSON().slice(0, 10); 
            this.currentdate = date.slice(0, 4) + '-'  
                       + date.slice(5, 7) + '-'  
                       + date.slice(8, 10); 
            switch (this.step) {
                //error handling for page 1, mostly modulename, module dates
                case 1:
                    if (!this.module) {
                        this.addError("Module name required!");
                    }
                    else if (this.module.length < 5) {
                        this.addError("Module name cannot be lesser than 5 characters");
                    }
                    else if (this.module.length > 50) {
                        this.addError("Module name cannot be more than 50 characters");
                    }
                    else if (!this.startdate) {
                        this.addError("Start date required!");
                    }
                    else if (!this.enddate) {
                        this.addError("End date required!");
                    }
                    else if (this.startdate < this.currentdate) {
                        this.addError("Start date cannot be in the past!");
                    }
                    else if (this.enddate < this.currentdate) {
                        this.addError("End date cannot be in the past!");
                    }
                    else if (this.enddate < this.startdate) {
                        this.addError("End date cannot be earlier than start date!");
                    }
                    break;
                //error handling on page2, mainly on selecting a moduleid(refer to ajax) 
                //as well as weightages and parameters on assessments and subassessments
                case 2:
                    if (this.assessments.length > 0) {
                        this.checkAssessments();
                        await axios.post("ajaxfile.php", {
                            request: 7,
                            moduleno: this.moduleno
                        }).then((response) => {
                            var res = response.data.split("\n");
                            this.moduleno = res.pop();
                        });
                    }
                    break;
                //error handling on csv import, checking if students inside the file are valid ids
                case 3:
                    if (this.students.length <= 0) {
                        this.addError("There are no students!");
                    } else {
                        var count = 0;
                        // Query database to check if ALL ids in this.students match an existing id
                        await axios.post("ajaxfile.php", {
                            request: 6,
                            studentids: this.students
                        }).then((response) => {
                            var res = response.data.split("\n");
                            count = res.pop();
                            if (Number(count) !== this.students.length) {
                                this.addError("There are invalid ids in your .csv!");
                            }
                        });
                    }
                    break;
                default:
                    break;
            }
            if (this.errors.length === 0) {
                this.$set(this, "step", this.step + 1);
            }
        },
        handleUpload(event) {
            const file = event.target.files[0];
            this.fileName = file.name;
            const reader = new FileReader();
            var ids = [];
            reader.onload = event => {
                if (this.fileName.endsWith(".csv")) {
                    var result = event.target.result;
                    result = result.split(/\r\n|\n/);
                    for (const id of result) {
                        if (id !== "id" && id.trim().length > 0) {
                            ids.push(id.trim());
                        }
                    }
                    this.students = ids;
                } else {
                    this.students = [];
                    alert("Please upload only .csv files!");
                }
            };
            reader.readAsText(file);
        },
        prevStep() {
            if (this.step > 1) {
                this.errors = [];
                this.$set(this, "step", this.step - 1);
            }
        },
        //parses variables over to the ajax file, check ajaxfile for more information on what each requests do
        addModule: function () {
            if (this.module !== '' && this.startdate !== '' && this.enddate !== '') {
                axios.post('ajaxfile.php', {
                    request: 1,
                    module: this.module,
                    startdate: this.startdate,
                    enddate: this.enddate,
                    assessment: this.assessments,
                    moduleno: this.moduleno
                })
                for (i = 0; i < this.assessments.length; i++) {
                    axios.post('ajaxfile.php', {
                        request: 2,
                        assessmentid: i + 1,
                        category: this.assessments[i].category,
                        assessmentweightage: this.assessments[i].weightage,
                        moduleno: this.moduleno
                    })
                }
                for (i = 0; i < this.assessments.length; i++) {
                    for (j = 0; j < this.assessments[i].subAssessments.length; j++) {
                        axios.post('ajaxfile.php', {
                            request: 3,
                            assessmentid: i + 1,
                            subassessmentname: this.assessments[i].subAssessments[j].name,
                            subassessmentweightage: this.assessments[i].subAssessments[j].weightage,
                            moduleno: this.moduleno
                        })
                    }
                }
                for (i = 0; i < this.students.length; i++) {
                    axios.post('ajaxfile.php', {
                        request: 4,
                        student: this.students[i],
                        moduleno: this.moduleno
                    })
                }
                for (i = 0; i < this.assessments.length; i++) {
                    for (j = 0; j < this.assessments[i].subAssessments.length; j++) {
                        for (k = 0; k < this.students.length; k++) {
                            axios.post('ajaxfile.php', {
                                request: 5,
                                assessmentid: i + 1,
                                subassessmentname: this.assessments[i].subAssessments[j].name,
                                student: this.students[k],
                                moduleno: this.moduleno
                            })
                        }
                    }
                }
            } else {
                alert('Fill all fields.');
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
                    subAssessments: [{name: "", weightage: 0}],
                });
            }
        },
        removeAssessment(index) {
            if (this.assessments.length > 1) {
                this.assessments.splice(index, 1);
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
            if (this.assessments.length > 0) {
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
        }
            // Check for unique assessment categories
            if (selectedCategories.size !== this.assessments.length) {
                this.addError("Please choose unique categories for all assessments");
            }
            // Check assessment weightages
            else if (currentTotalWeightage !== eval(this.totalWeightage)) {
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