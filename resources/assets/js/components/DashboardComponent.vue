<template>
    <div class="container-fluid main_content">
        <div class="row">
            <div class="col-sm-12 col-lg-12 col-md-12 main_right_box">
              <div class="card_inner">
                <div class="inner_title">
                    Upload Documents for a faster loan approval <span v-show="showFileNumber.length">for HDFC Loan Account No : {{showFileNumber}}</span><br>
                </div>

                <div class="note_text mt-2">
                            <span class="mt-4">
                                For faster processing of your loan application please upload a pdf/jpeg file of your income related documents and net downloaded bank statements. This link may be used to upload:
                            </span>

                    <ul class="ml-4 mt-3 ">
                        <li> Last 3 months’ salary slips for all applicants </li>
                        <li> Form 16 for all salaried customers </li>
                        <li> Last 6 months’ Bank statements, showing salary credits </li>
                        <li> Last 6 months’ Bank statements of all other ACTIVE bank accounts operated by you </li>
                        <li>
                            Other documents like Appointment/ CTC letter, Increment letter, documents related to Property, etc.
                        </li>
                    </ul>
                </div>

                <div class="row margin-top30 text-center">
                    <div class="col-lg-12">
                        Kindly upload the documents here.
                    </div>
                    <div class="col-lg-12 margin-top10">
                        <input class="btn btn-outline-primary btn-upload" @click="sendUploadData({DOC_TYPE : 'OTHER', APPL_CUST_NUMBER : '1', DTL_SRNO : ''})" data-toggle="modal" data-target="#mod-reply"
                               type="submit" value="Upload Documents">
                    </div>

                    <div class="col-lg-12 note_text mt-2 margin-bottom30">
                        The file supported format type are JPEG, JPG, PNG or PDF.<br>
                        The maximum uploadable file size is 15MB.
                    </div>
                </div>


                <div class="row">

                    <!--Pending doc list -->
                    <div v-for="(docArray, key) in pendingDocList"
                         class="card bg-light col-lg-7 col-md-7 col-sm-12 margin-top20" style="padding: 0px;">
                        <div class="card-header w-100 font-600" style="text-transform: capitalize;">
                            {{docArray[0].CUST_NAME}}
                        </div>
                        <div v-for="(docs, index) in docArray" class="card-body w-100">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-7">
                                    {{ docs.DOC_DESCRIPTION }} <br>
                                    <span class="font-400 font14"> {{ docs.DIGITAL_DOC_SUB_DESC }} </span>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-5 text-right padding-right-20">
                                    <input class="btn btn-outline-primary btn-upload" data-toggle="modal"
                                           @click="sendUploadData(docs)"
                                           data-target="#mod-reply" type="submit" value="Upload"
                                           :data-doc_desc="docs.DOC_DESCRIPTION"
                                           :data-doc_sub_desc="docs.DIGITAL_DOC_SUB_DESC">
                                </div>
                            </div>

                            <hr class="line">

                        </div>
                    </div>


                    <!-- Password doc list -->
                    <div v-if="passwordDocList.length" class="card bg-light col-lg-7 col-md-7 col-sm-12 margin-top20"
                         style="padding: 0px;">
                        <div class="card-header w-100 font-600" style="text-transform: capitalize;">
                            Password Protected Docs
                        </div>
                        <div v-for="(docs, index) in passwordDocList" class="card-body w-100">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-7">
                                    {{ docs.ORIGINAL_FILE_NAME }}<br>
                                    <span class="font-400 font14 password-error"></span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-5 text-right padding-right-20">
                                    <form @submit.prevent="checkPassword(docs.DTL_SRNO, 'form'+index, index)"
                                          class="frmPassword" name="frmPassword" method="post"
                                          :data-vv-scope="'form'+index">
                                        <div class="row margin-top10 text-center">
                                            <div class="col-lg-8 col-sm-12 col-md-8">

                                                <input type="password" v-validate="'required'" name="password"
                                                       v-model="password[index]" class="form-control"
                                                       placeholder="Enter Password">
                                                <span v-show="errors.has('form'+index+'.password')"
                                                      class="help text-danger">{{ errors.first('form' + index + '.password')
                                                    }}</span>
                                            </div>

                                            <div class="col-lg-4 col-sm-12 col-md-4">
                                                <button class="btn btn-primary btn-save" type="submit">Save</button>
                                                <!--:disabled="errors.any('form'+index)"-->
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr v-if="passwordDocList.length != (index+1)" class="line">
                        </div>
                    </div>

                </div>


                <UploadModalPopUp :customerDetails="customerDetails"
                                  :specificFileDetails="sendSpecificFileDetails"
                ></UploadModalPopUp>

                <div class="row">

                    <!--   Received doc list -->
                    <div v-if="receivedDocList.length" class="card bg-light col-lg-7 col-md-7 col-sm-12 margin-top20"
                         style="padding: 0px;">
                        <div class="card-header w-100 font-600" style="text-transform: capitalize;"> Received Files
                        </div>
                        <div v-for="(docs, index) in receivedDocList" class="card-body w-100">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-7">
                                    <span v-if="docs.CUST_NAME != ''"> {{docs.CUST_NAME}} - </span>
                                    {{docs.DOC_TYPE_DESC}}<br>
                                    <span class="font-400 font14"> {{docs.ORIGINAL_FILE_NAME}} </span>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-5 text-right padding-right-20">
                                    <form :action="viewDocRoute" target="_blank" method="post">

                                        <input type="hidden" name="_token" :value="csrfToken">

                                        <input type="hidden" name="req_srno" :value="docs.DIGITAL_DOC_SRNO">
                                        <input type="hidden" name="dtl_srno" :value="docs.DTL_SRNO">
                                        <button type="submit" class="btn btn-outline-primary btn-upload btn-view">View
                                        </button>
                                    </form>
                                </div>

                            </div>
                            <hr v-if="receivedDocList.length != (index+1)" class="line">
                        </div>
                    </div>

                </div>

              </div>

            </div>
        </div>

    </div>


</template>

<script>


    import vueDropzone from "vue2-dropzone";

    Vue.component('vueDropzone', vueDropzone);  // custom component name

    import 'vue2-dropzone/dist/vue2Dropzone.css';


    import UploadModal from './UploadModal.vue';

    Vue.component('UploadModalPopUp', UploadModal);  // custom component name

    export default {
        props: ['documentInformation'],
        data: function () {
            return {
                password: [],
                customerDetails: this.documentInformation.DATA.customerDetails,
                passwordDocs: this.documentInformation.DATA.passwordDocList,
                pendingDocs: this.documentInformation.DATA.pendingDocList,
                receivedDocs: this.documentInformation.DATA.receivedDocList,
                viewDocRoute: route('viewDocument'),
                fileData: {},
                html: '',
            }
        },
        methods: {
            checkPassword: function (sr_no, scope, index) {
                this.$validator
                    .validateAll(scope)
                    .then((response) => {

                        if (!response) {
                            return;
                        }

                        axios.post(route('api.password.update'), {password: this.password[index], sr_no: sr_no})
                            .then((resp) => {
                                if (resp.data.status) {
                                    this.getAllDocument();
                                }
                            })
                            .catch((e) => {
                                this.errors.add({field: `${scope}.password`, msg: e.response.data.message});

                                 setTimeout(() => {
                                     this.errors.clear();
                                 }, 3000);

                            });


                    })
                    .catch((e) => {
                        console.log(e);
                    });
            },
            getAllDocument: function () {
                axios.post(route('api.doc.list')).then((response) => {
                    //this.isLoading = false;
                    // this.documents = response.data.DATA;

                    this.passwordDocs = response.data.DATA.passwordDocList;
                    this.pendingDocs = response.data.DATA.pendingDocList;
                    this.receivedDocs = response.data.DATA.receivedDocList;

                }).catch(function (error) {
                    //this.isLoading = false;
                    console.log(error);
                });
            },
            sendUploadData: function (docsData) {
                this.fileData = docsData;
            },
        },
        mounted() {
            //$vm0.doc_data.original.data.customerDetails.CUST_NAME
            //this.customerDetails.cust_name = this.doc_data.original.data.customerDetails.CUST_NAME;
            //this.customerDetails.file_no = this.doc_data.original.data.customerDetails.FILE_NO;
            // console.log('hiii'+this.cust_name);
            //console.log(this.doc_data);
        },
        computed: {
            sendSpecificFileDetails: function () {
                return this.fileData;
            },
            showFileNumber: function () {
                return this.customerDetails.FILE_NO;
            },
            passwordDocList: function () {
                return this.passwordDocs;
            },
            pendingDocList: function () {
                return this.pendingDocs;
            },
            receivedDocList: function () {
                return this.receivedDocs;
            },
            csrfToken: function () {
                return document.head.querySelector('meta[name="csrf-token"]').content;
            }
        },
    }
</script>


<style>
    .dropzone {
        border: 2px dashed #0087F7;
        border-radius: 5px;
        background: white;
    }

    .upload-icon {
        font-size: 35px;
    }

    .dropzone .dz-preview {
        margin: 0px 50px;
    }

    .dz-error-message {
        top: -69px !important;
        display: none;
    }

    .dropzone .dz-preview.dz-error:hover .dz-error-message {
        opacity: 0 !important;
    }

    .dz-error-message:after {
        border-top: 6px solid #be2626 !important;
        border-bottom: none !important;
        top: 51px !important;
        display: none;
    }

    .btn-save {
        padding: 5px 13px;
        font-size: 13px;
        margin-top: 10px;
    }

    .btn-view {
        padding: 4px 18px;
        font-size: 14px;
    }
</style>