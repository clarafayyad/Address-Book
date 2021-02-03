<template>
    <div class="container">
        <div class="row">
            <h1>Address Book</h1>
        </div>

        <hr>

        <div class="row">
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="First Name *" v-model="newContact.FirstName" required/>
            </div>
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="Last Name *" v-model="newContact.LastName" required/>
            </div>
            <div class="form-group col-md-3">
                <input class="form-control" type="text" placeholder="Phone Number *" v-model="newContact.PhoneNumber" required/>
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
                <input class="form-control" type="email" placeholder="Email" v-model="newContact.Email" required/>
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

        <div class="row">
            <div class="form-group col-md-3">
                <select class="form-control" name="entityType" v-model="searchEntity.type">
                    <option value="all">All</option>
                    <option value="firstName">First name</option>
                    <option value="lastName">Last name</option>
                    <option value="phoneNumber">Phone number</option>
                    <option value="job">Job</option>
                    <option value="address">Address</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <input :disabled="searchEntity.type=='all'" class="form-control" type="text" placeholder="Search" v-model="searchEntity.searchQuery" required/>
            </div>
            <div class="col-md-3">
                <b-button id="searchButton" variant="info" :disabled="searchIsDisabled" v-on:click="search(1)">
                    Search
                    <b-spinner small v-if="searchIsDisabled" label="spinning"></b-spinner>
                </b-button>
            </div>
            <div class="col-md-12">
                <p class="text-danger">{{searchErrorMessage}}</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <h3>Contacts</h3>
            </div>
            <div class="col-md-12 settings-table">
                <div v-if="isLoading" class="d-flex justify-content-center mb-3">
                    <b-spinner style="width: 5rem; height: 5rem;" type="grow"></b-spinner>
                </div>
                <table v-else class="table">
                    <thead>
                        <th class="imageColumn"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Job</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                     <tr v-for="item in this.contacts" v-bind:key="item.id">
                         <td class="imageColumn">
                             <b-img  class="contactImage" v-bind:src="item.imageSrc"  rounded="circle" alt="Circle image"></b-img>
                         </td>
                         <td>{{item.id}}</td>
                         <td>{{item.name}}</td>
                         <td>{{item.phoneNumber}}</td>
                         <td>{{item.email}}</td>
                         <td>{{item.address}}</td>
                         <td>{{item.job}}</td>
                         <td>
                             <a v-on:click="deleteContact(item.id)" id="deleteOption">
                                 <i class="fa fa-trash"></i>
                             </a>
                             <a v-on:click="showModal(item.id, item.name, item.imageSrc, item.phoneNumber)">
                                 <i class="fa fa-cog"></i>
                             </a>
                         </td>
                     </tr>
                    </tbody>
                </table>

                <b-pagination
                  v-model="currentPage"
                  :total-rows="rows"
                  :per-page="perPage"
                  v-on:click.native="changePage()"
                  align="right"
                ></b-pagination>
            </div>

             <b-modal  size="xl" ok-only ok-variant="secondary" ok-title="Cancel" ref="my-modal" id="modal-center" centered title="Contact Details" large>
                 <table>
                     <tr>
                         <td>
                             <b-img  class="contactImage" v-bind:src="selectedContact.image"  rounded="circle" alt="Circle image"></b-img>
                         </td>
                         <td>
                             <h3>{{this.selectedContact.name}}</h3>
                         </td>
                     </tr>
                 </table>
                 <hr>
                 <p>Change Phone Number:</p>
                <div class="row">
                    <div class="form-group col-md-3">
                        <input class="form-control" type="text" v-model="selectedContact.newPhoneNumber" required/>
                    </div>
                    <div class="col-md-3">
                        <button :disabled="updateIsDisabled" class="btn btn-secondary" v-on:click="updateContact">
                            Update Contact
                            <b-spinner small v-if="updateIsDisabled" label="spinning"></b-spinner>
                        </button>
                    </div>
                </div>
                 <p class="text-danger">{{modalErrorMessage}}</p>
                 <p>Change Image:</p>
                 <div class="form-group col-md-12">
                      <input type="file" id="file" ref="file" accept="image/*"/>
                     <button class="btn btn-secondary" type="button" @click='uploadFile()'>Upload File</button>
                </div>
                 <hr>
                 <div class="row">
                     <div class="col-md-12">
                         <h4>Relationships</h4>
                     </div>
                     <div class="col-md-4">
                         <input class="form-control" type="text" placeholder="Contact ID" v-model="newRelationship.contactId" required/>
                     </div>
                     <div class="col-md-4">
                         <select class="form-control" name="entityType" v-model="newRelationship.type">
                            <option value="friend">Friend</option>
                            <option value="family">Family</option>
                            <option value="spouse">Spouse</option>
                            <option value="sibling">Sibling</option>
                            <option value="colleague">Colleague</option>
                            <option value="acquaintance">Acquaintance</option>
                        </select>
                     </div>
                     <div class="col-md-4">
                         <b-button variant="info" v-on:click="addRelationship()">Add Relationship</b-button>
                     </div>
                     <div class="col-md-12">
                         <p class="text-danger">{{this.addRelationshipError}}</p>
                     </div>
                 </div>
                 <hr>
                 <div class="row">
                     <div class="col-md-12">
                         <table v-if="this.modalRelationshipContacts.length!=0" class="table">
                            <thead>
                                <th>Relationship Type</th>
                                <th>Contact ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                             <tr v-for="item in this.modalRelationshipContacts" v-bind:key="item.RelationshipId">
                                 <td>{{item.RelationshipType}}</td>
                                 <td>{{item.ContactId}}</td>
                                 <td>{{item.Name}}</td>
                                 <td>{{item.PhoneNumber}}</td>
                                 <td>
                                     <a v-on:click="deleteRelationship(item.RelationshipType, item.ContactId, selectedContact.id)">
                                         <i class="fa fa-trash"></i>
                                     </a>
                                 </td>
                             </tr>
                            </tbody>
                        </table>
                     </div>
                 </div>
             </b-modal>

        </div>
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
            this.isLoading        = false;
            this.addIsDisabled    = false;
            this.deleteIsDisabled = false;
            this.updateIsDisabled = false;
            this.searchIsDisabled = false
            this.isSearching      = false;

            this.searchEntity.type    = 'all';
            this.newRelationship.type = 'friend';

            this.getAllContacts();
        },
        data: () => {
            return {
                isLoading : '',
                isSearching: '',
                addIsDisabled:'',
                deleteIsDisabled:'',
                updateIsDisabled:'',
                searchIsDisabled:'',
                errorMessage: '',
                modalErrorMessage: '',
                searchErrorMessage:'',
                contacts: [],
                newContact: {FirstName: '', LastName: '', PhoneNumber: '', Address: '', JobTitle: '', Email: ''},
                selectedContact: {id: '',image:'', name:'', oldPhoneNumber: '', newPhoneNumber: ''},
                searchEntity: {type: '', searchQuery:''},
                perPage: 10,
                currentPage: 1,
                rows: 0,
                modalRelationshipContacts: '',
                newRelationship:{ contactId: '', type: ''},
                addRelationshipError: '',
            }
        },
        methods: {
            changePage() {
                this.search(this.currentPage);
            },
            getAllContacts(){
                this.searchEntity.searchQuery = '';
                this.searchEntity.type = 'all';
                this.currentPage = 1;
                this.search(this.currentPage);
            },
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

                let isNum = /^[+]?[0-9]+$/.test(phoneNumber);
                if(!isNum){
                    this.errorMessage = 'Please enter a valid phone number';
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
                            if(this.matchSearchCriteria(firstName, lastName, phoneNumber, job, address, email)){
                                this.contacts.unshift({id: response.data.id,
                                                             name: firstName + " " +lastName,
                                                             phoneNumber: phoneNumber,
                                                             email: email,
                                                             job: job,
                                                             address:address,
                                                             imageSrc: 'http://localhost/addressbook/API/uploads/contact-icon.png',
                                });
                            }else{
                                this.getAllContacts();
                                this.emptySearchingDetails();
                            }
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
            deleteContact(id){
                this.isLoading = true;

                 axios({
                    url: "http://localhost/addressbook/API/DELETEContact.php",
                    params: { contactId: id,},
                }).then(response => {
                     if(response.statusText == 'OK') {
                         let removeIndex = this.findIndex(this.contacts, id);
                         this.contacts.splice(removeIndex,1);
                         miniToastr.success("Contact deleted successfully", 'Success');
                     }else{
                         miniToastr.error("Could not delete contact", 'Error');
                     }

                    this.isLoading = false;
                    return response;

                }).catch( (error) => {
                    this.isLoading = false;
                    miniToastr.error("Could not delete contact", 'Error');
                    console.log(error);
                });
            },
            updateContact(){
                this.modalErrorMessage = '';

                if(this.selectedContact.newPhoneNumber.length == 0){
                    this.modalErrorMessage = 'Please specify the new phone number';
                    return false;
                }

                if(this.selectedContact.newPhoneNumber == this.selectedContact.oldPhoneNumber){
                    this.modalErrorMessage = 'Please enter a different phone number to update this contact';
                    return false;
                }

                let isNum = /^\d+$/.test(this.selectedContact.newPhoneNumber);
                if(!isNum){
                    this.modalErrorMessage = 'The phone number must only include digits';
                    return;
                }

                this.modalErrorMessage = '';
                this.updateIsDisabled  = true;

                axios({
                    url: "http://localhost/addressbook/API/PATCHContact.php",
                    params:
                        {
                            contactId: this.selectedContact.id,
                            oldPhoneNumber: this.selectedContact.oldPhoneNumber,
                            newPhoneNumber: this.selectedContact.newPhoneNumber,
                        },
                }).then(response => {
                    if(response.statusText == 'OK') {
                        if(response.data == "This number exists"){
                            miniToastr.error("This number exists", 'Error');
                        }else{
                            let updateIndex = this.findIndex(this.contacts, this.selectedContact.id);
                            this.contacts[updateIndex].phoneNumber = this.selectedContact.newPhoneNumber;
                            miniToastr.success("Contact updated successfully", 'Success');
                        }
                    }

                    this.updateIsDisabled = false;
                    this.$refs['my-modal'].hide();
                    this.clearModal();
                    return response;

                }).catch( (error) => {
                    this.$refs['my-modal'].hide();
                    this.updateIsDisabled = false;
                    miniToastr.error("Could not update contact", 'Error');
                    this.clearModal();
                    console.log(error);
                });
            },
            search(page){
                this.currentPage = page;

                if(this.searchEntity.type == 'all'){
                    this.emptySearchingDetails();
                }else {
                    this.isSearching = true;
                }

                this.searchErrorMessage = ''
                this.contacts           = [];
                this.isLoading          = true;

                axios({
                    url: "http://localhost/addressbook/API/GETContacts.php",
                    method: 'get',
                    params: {
                        pageNumber:  page,
                        searchQuery: this.searchEntity.searchQuery,
                        type:        this.searchEntity.type,
                        isSearching: this.isSearching,
                    }
                }).then(response => {
                    this.isLoading   = false;
                    if(response.statusText == "OK"){
                        this.contacts    = response.data.data;
                        this.rows        = response.data.count;
                    }else{
                        miniToastr.error("Could not get results", "Error");
                    }
                    return response;
                })
                .catch((error) => {
                    this.isLoading = false;
                    console.log(error);
                    miniToastr.error("Could not get results", "Error");
                });
            },
            uploadFile(){
               this.file = this.$refs.file.files[0];

               let formData = new FormData();
               formData.append('file', this.file);
               formData.append('contactId', this.selectedContact.id);

               axios.post("http://localhost/addressbook/API/PATCHImage.php", formData,
               {
                  headers: {
                    'Content-Type': 'multipart/form-data'
                  }
               })
               .then(response => {
                  if(!response.data){
                     miniToastr.error("Could not update image", 'Error');
                  }else{
                      let index = this.findIndex(this.contacts, this.selectedContact.id);
                      this.contacts[index].imageSrc = response.data.fileDestination;
                      miniToastr.success("Image successfully updated", 'Success');
                  }
                   this.$refs['my-modal'].hide();
                   this.clearModal();
                   return response;
               })
               .catch(error => {
                   console.log(error);
                   miniToastr.error("Could not update image", 'Error');
                   this.$refs['my-modal'].hide();
                   this.clearModal();
                   });
            },
            deleteRelationship(relationshipType, contactId1, contactId2){

                if(!relationshipType || !contactId2 || !contactId1){
                    miniToastr.error('Could not delete relationship', 'Error');
                    return;
                }

                 axios({
                    url: "http://localhost/addressbook/API/DELETERelationship.php",
                    params: {
                        contactId1: contactId1,
                        contactId2: contactId2,
                        relationshipType: relationshipType
                    },
                }).then(response => {
                     if(response.statusText == 'OK') {
                         let removeIndex = -99;
                         for( let i = 0; i<this.modalRelationshipContacts.length; i++){
                             if((this.modalRelationshipContacts[i].ContactId == contactId1) &&
                                 (this.modalRelationshipContacts[i].RelationshipType == relationshipType)){
                                 removeIndex = i;
                             }
                         }
                         if(removeIndex != -99){
                             this.modalRelationshipContacts.splice(removeIndex,1);
                            miniToastr.success("Relationship deleted successfully", 'Success');
                         }
                     }else{
                         miniToastr.error("Could not delete relationship", 'Error');
                     }

                    return response;

                }).catch( (error) => {
                    miniToastr.error("Could not delete relationship", 'Error');
                    console.log(error);
                });
            },
            showModal(id, name, image, oldPhoneNumber){
                this.selectedContact.id             = id;
                this.selectedContact.name           = name;
                this.selectedContact.image          = image;
                this.selectedContact.oldPhoneNumber = oldPhoneNumber;
                this.selectedContact.newPhoneNumber = oldPhoneNumber;
                this.modalErrorMessage              = '';
                this.addRelationshipError           = '';

                axios({
                    url: "http://localhost/addressbook/API/GETRelationships.php",
                    method: 'get',
                    params: {
                        id:  id,
                    }
                }).then(response => {
                    if(response.statusText == "OK"){
                        this.modalRelationshipContacts  = response.data;
                        this.$refs['my-modal'].show();
                    }else{
                        miniToastr.error("Could not get relationships", "Error");
                    }
                    return response;
                })
                .catch((error) => {
                    console.log(error);
                    miniToastr.error("Could not get relationships", "Error");
                });
            },
            addRelationship(){
                this.addRelationshipError = '';

                let relationshipType  = this.newRelationship.type;
                let relationshipContactId = this.newRelationship.contactId;

                if(!relationshipContactId){
                    this.addRelationshipError = 'Please enter the contact id';
                    return;
                }

                if(relationshipContactId == this.selectedContact.id){
                    this.addRelationshipError = 'cannot add a relationship to this same contact';
                    return;
                }
                axios({
                    url: "http://localhost/addressbook/API/POSTRelationship.php",
                    method: 'post',
                    params:
                        {
                            type : relationshipType,
                            fromId: this.selectedContact.id,
                            toId: relationshipContactId,
                        }
                }).then(response => {
                        if(response.statusText == 'OK') {
                            if(response.data == 'this relationship exists' || response.data == 'this contact does not exist') {
                                miniToastr.error(response.data, 'Error');
                            }else{
                                this.modalRelationshipContacts.unshift({RelationshipType: relationshipType,
                                                                              ContactId: relationshipContactId,
                                                                              Name: response.data.ContactName,
                                                                              PhoneNumber: response.data.ContactPhoneNumber,
                                });
                            }
                        }else {
                            miniToastr.error('Could not add relationship', 'Error');
                        }
                        this.emptyNewRelationshipDetails();
                        return response;
                }).catch( (error) => {
                    miniToastr.error("Could not add relationship", 'Error');
                    console.log(error);
                    this.emptyNewRelationshipDetails();
                });
            },
            clearModal(){
                this.selectedContact.id = '';
                this.selectedContact.name = '';
                this.selectedContact.image = '';
                this.selectedContact.newPhoneNumber = '';
                this.selectedContact.oldPhoneNumber = '';
            },
            emptySearchingDetails(){
              this.isSearching = false;
              this.searchEntity.type = 'all';
              this.searchEntity.searchQuery  = '';
            },
            findIndex(list, target){
                for(let i = 0; i<list.length; i++){
                    if(list[i].id == target){
                        return i;
                    }
                }
            },
            matchSearchCriteria(firstName, lastName, phoneNumber, job, address, email){
                let query = this.searchEntity.searchQuery.toLowerCase();
                switch(this.searchEntity.type){
                    case 'all':
                        return true;
                    case 'firstName':
                        return (firstName.toLowerCase()).includes(query);
                    case 'lastName':
                        return (lastName.toLowerCase()).includes(query);
                    case 'phoneNumber':
                        return(phoneNumber.includes(query));
                    case 'job':
                        return (job.toLowerCase()).includes(query);
                    case 'address':
                        return (address.toLowerCase()).includes(query);
                    case 'email':
                        return (email.toLowerCase()).includes((query));
                }
            },
            emptyNewContactDetails(){
                this.newContact.FirstName   = '';
                this.newContact.LastName    = '';
                this.newContact.PhoneNumber = '';
                this.newContact.Email       = '';
                this.newContact.Address     = '';
                this.newContact.JobTitle    = '';
            },
            emptyNewRelationshipDetails(){
                this.newRelationship.contactId = '';
                this.newRelationship.type      = 'friend';
                this.addRelationshipError      = '';
            }
        }
    }
</script>

<style>
    .spinner-border {
        height: 15px;
        width: 15px;
    }

    .page-item.active .page-link {
    background-color: grey !important;
    border-color: grey !important;
    }

    .contactImage{
        width: 50px;
        height: 50px;
    }

    .imageColumn{
        width: 1px;
        height: 1px;
    }

    #searchButton{
        width: 115px;
    }

    #deleteOption{
        padding-right: 10px;
    }

</style>