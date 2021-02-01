<template>
    <div class="container">
        <div class="row">
            <h1>Address Book</h1>
        </div>

        <hr>

        <div class="row">
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="First Name" v-model="newContact.FirstName" required/>
            </div>
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="Last Name" v-model="newContact.LastName" required/>
            </div>
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="PhoneNumber" v-model="newContact.PhoneNumber" required/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="Address" v-model="newContact.Address" required/>
            </div>
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="Job" v-model="newContact.JobTitle" required/>
            </div>
             <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="Email" v-model="newContact.Email" required/>
            </div>
            <div class="col-md-3">
                <b-button class="btn btn-primary" :disabled="addIsDisabled" v-on:click="addContact()">
                    Add Contact
                    <b-spinner small v-if="addIsDisabled" label="spinning"></b-spinner>
                </b-button>
            </div>
            <div class="col-md-12">
                <p class="text-danger">{{errorMessage}}</p>
            </div>
        </div>

        <hr>

    </div>
</template>
<script>
    import axios from 'axios';
    import miniToastr from 'mini-toastr';
    miniToastr.init();

    export default {
        name: "address_book",
        mounted() {
            // when the component has loaded
            this.addIsDisabled    = false;
        },
        data: () => {
            return {
                addIsDisabled:'',
                errorMessage: '',
                newContact: {FirstName: '', LastName: '', PhoneNumber: '', Address: '', JobTitle: '', Email: ''},
                contacts: [],
            }
        },
        methods: {
            addContact(){
                this.errorMessage = '';

                let firstName   = this.newContact.FirstName;
                let lastName    = this.newContact.LastName;
                let phoneNumber = this.newContact.PhoneNumber;
                let email       = this.newContact.Email;
                let job         = this.newContact.JobTitle;
                let address     = this.newContact.Address;

                if(!firstName){
                    this.errorMessage = 'Please enter the first name ';
                    return;
                }

                if(!lastName){
                    this.errorMessage = 'Please enter the last name';
                    return;
                }

                if(!phoneNumber){
                    this.errorMessage = 'Please enter the phone number';
                    return;
                }

                let isNum = /^\d+$/.test(phoneNumber);
                if(!isNum){
                    this.errorMessage = 'The phone number must only include digits';
                    return;
                }


                this.addIsDisabled = true;

                axios({
                    url: "http://localhost/addressbook/API/POSTContact.php",
                    method: 'post',
                    params:
                        {
                            firstName:   firstName,
                            lastName:    lastName,
                            phoneNumber: phoneNumber,
                            email:       email,
                            job:         job,
                            address:     address,
                        }
                }).then(response => {
                        this.addIsDisabled = false;
                        if(response.statusText == 'OK') {
                            if(response.data == 'this number exists'){
                                miniToastr.error(response.data, 'Error');
                                return;
                            }
                            miniToastr.success("Contact added successfully", 'Success');
                        }else {
                            miniToastr.error('Could not add Contact', 'Error');
                        }
                        this.emptyNewContactDetails();
                        return response;
                }).catch( (error) => {
                    this.addIsDisabled = false;
                    miniToastr.error("Could not add contact", 'Error');
                    console.log(error);
                    this.emptyNewContactDetails();
                });
            },
            emptyNewContactDetails(){
                this.newContact.FirstName   = '';
                this.newContact.LastName    = '';
                this.newContact.PhoneNumber = '';
                this.newContact.Email       = '';
                this.newContact.Address     = '';
                this.newContact.JobTitle    = '';
            }
        }
    }
</script>

<style>
    .spinner-border {
        height: 15px;
        width: 15px;
    }
</style>