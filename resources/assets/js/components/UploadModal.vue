<template>
    <div id="mod-reply" tabindex="-1" role="dialog" style="" class="modal fade" ref="uploadVueModalRef">
        <div class="modal-dialog modal-lg mt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="inner_title border-0 padding0">
                        <span class="doc_sub_head">Upload Documents</span>
                        <span class="font-400 font14 doc_sub_desc">
                    </span>
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" ref="modalBody">
                    <div class="row margin-bottom30 upload-main" v-if="!showThankYouIcon">
                        <div class="col-lg-12 col-md-12">
                            <nav class="hide">
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                                       href="#nav-home"
                                       role="tab" aria-controls="nav-home" aria-selected="true">Through Netbanking</a>
                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                                       href="#nav-profile"
                                       role="tab" aria-controls="nav-profile" aria-selected="false">From Storage</a>
                                </div>
                            </nav>
                            <div class="tab-content borderbox" id="nav-tabContent">

                                <div v-show="showErrorMessage" class="per-msg p-2 text-danger text-center">
                                    {{ errorMessage }}
                                </div>

                                <div class="tab-pane fade pt-3 hide" id="nav-home" role="tabpanel"
                                     aria-labelledby="nav-home-tab">
                                    Your will be redirected to Bank Netbanking This facility is powered by Perfios.<br><br>
                                    <form id="frmPerfios" name="frmPerfios" method="post" target="perfios_popup"
                                          action="'perfios-request'"
                                          onsubmit="if(!this.terms.checked){document.getElementById('term-msg').innerHTML = 'You must agree to the terms first.';return false}else{ window.open('about:blank','perfios_popup','width=1000,height=800');}">
                                        <input type="hidden" name="doc_sr_no" value="32164">
                                        <input type="hidden" name="doc_ref_tab" value="ADDITIONAL_DOC">

                                        <div class="row margin-top10 text-center">
                                            <div class="col-lg-12 col-sm-12 col-md-12" style="font-size: 12px;">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="terms" name="terms"
                                                                  class="form-control1">
                                                        I accept terms and conditions.</label>
                                                </div>
                                                <div id="term-msg" class="text-danger"></div>
                                            </div>
                                        </div>


                                        <div class="row margin-top30">
                                            <div class="col-lg-12 text-center">
                                                <button class="btn btn-primary btn-submit" type="submit">Continue
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel"
                                     aria-labelledby="nav-profile-tab">
                                    <div class="local-upload">

                                        <form @submit.prevent="validateSubmitForm()" name="frmDropzone"
                                              id="frmDropzone">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">

                                                    <!--//if(doc_type === 'OTHER') -->
                                                    <div class="form-group row" style="display: none">
                                                        <label for="document_type"
                                                               class="col-3 col-form-label">DOC LIST</label>
                                                        <div class="col-9">
                                                            <select name="document_type" id="document_type"
                                                                    class="form-control" style="display: inline-block;">
                                                                <option value="" selected>select</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" id="kyc_docs_section"
                                                         style="display: none">
                                                        <label for="additional_docs" class="col-3 col-form-label">KYC Doc Type</label>
                                                        <div class="col-9">
                                                            <select name="additional_docs" id="additional_docs"
                                                                    class="form-control" style="display: inline-block;">
                                                                <option value="" selected>--select--</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" id="password_section"
                                                         v-if="showPasswordSection">
                                                        <label for="password"
                                                               class="col-3 col-form-label">Password</label>
                                                        <div class="col-9">
                                                            <input type="password" v-validate="'required'"
                                                                   name="password" key="password" id="password"
                                                                   v-model="password" class="form-control"
                                                                   style="display: inline-block;">
                                                            <span v-show="errors.has('password')"
                                                                  class="help text-danger">{{ errors.first('password')}}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <vue-dropzone
                                                    ref="myVueDropzone"
                                                    id="drop1"
                                                    :options="dropzoneOptions"
                                                    :useFontAwesome="true"
                                                    :destroyDropzone="false"
                                                    @vdropzone-removed-file="removeFile"
                                                    @vdropzone-error="fileError"
                                                    v-on:vdropzone-files-added="filesAdded"
                                                    v-on:vdropzone-success="showSuccess"
                                                    @vdropzone-complete="afterComplete"
                                                    v-on:vdropzone-sending="sendingEvent"
                                                    :useCustomSlot=true
                                            >
                                                <div class="dropzone-custom-content">
                                                    <h3 class="dropzone-custom-title">Drop files here!</h3>
                                                    <div class="subtitle">
                                                        ...or click to select a file from your computer
                                                    </div>
                                                    <i class="fa fa-cloud-upload upload-icon mt-2"></i>
                                                </div>
                                            </vue-dropzone>

                                            <div class="note_text mt-2">
                                                The file supported format type are JPEG, JPG, PNG or PDF.<br>
                                                The maximum uploadable file size is 15MB.
                                            </div>

                                            <div class="col-sm-5 col-md-5 col-md-offset-4 mt-3 text-center">
                                                <button type="submit" v-show="showUploadButton"
                                                        class="btn btn-xs btn-primary mt10">upload
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="thankyou_icon" :class="{active:showThankYouIcon}" v-show="showThankYouIcon">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        <div class="thankyou_icons active" style="display: none">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </div>
                        <div v-show="showThankYouIcon" :class="[showThankYouIcon ? 'text-success' : '']">
                            {{ errorMessage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>


<script>

    import vueDropzone from "vue2-dropzone";
    import AddMoreButton from "./AddMoreButton";

    Vue.component('vueDropzone', vueDropzone);  // custom component name

    import Vue from 'vue';
    import 'vue2-dropzone/dist/vue2Dropzone.css';

    export default {
        props: ['customerDetails', 'specificFileDetails'],
        data: function () {
            return {
                dropzoneOptions: {
                    url: route('api.doc.upload'),
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    maxFilesize: 15,  //MB
                    acceptedFiles: ".jpeg,.jpg,.png,.pdf",
                    parallelUploads: 10,
                    maxFiles: 10,
                    addRemoveLinks: true,
                    dictRemoveFile: '✘',
                    dictCancelUpload: '✘',
                    dictInvalidFileType: "Invalid File type. Kindly upload valid File.",
                    //chunking: true,    // it will chunk post request in many request
                    //chunkSize: 500, // Bytes
                    thumbnailWidth: 150, // px
                    thumbnailHeight: 150,
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i>UPLOAD ME",
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    }
                },
                uploadBtnHideShow: false,
                togglePasswordField: false,
                errorMessage: '',
                password: '',
                showThankYouIcon: false,
            }
        },
        components: {AddMoreButton},
        methods: {
            filesAdded: function (file) {
                this.uploadBtnToggle();

                var AddMoreBtnComponentClass = Vue.extend(AddMoreButton);
                var addBtnInstance = new AddMoreBtnComponentClass({
                    propsData: {toggleAddMoreBtn: this.uploadBtnHideShow, dropzone : this.$refs.myVueDropzone}
                });

                addBtnInstance.$slots.default = ['+'];
                addBtnInstance.$mount();

                if(this.$refs.myVueDropzone.$el.getElementsByClassName('add-more-btn').length)
                    this.$refs.myVueDropzone.$el.getElementsByClassName('add-more-btn')[0].remove();


                let appendTo = this.$refs.modalBody.getElementsByClassName('dz-preview');
                appendTo[appendTo.length - 1].after(addBtnInstance.$el);
            },
            sendingEvent: function (file, xhr, formData) {

                for (var data in this.documentExtraParameter)
                    formData.append(data, this.documentExtraParameter[data]);

            },
            showSuccess: function (file, response) {
                if (response.status) {
                    this.uploadBtnHideShow = false;
                    this.showThankYouIcon = true;
                    this.errorMessage = response.message;
                    this.$parent.getAllDocument();
                    return;
                }
            },
            afterComplete: function (file) {
                console.log('im in afterComplete');
                console.log(file);
            },
            removeFile: function (file) {
                this.errorMessage = '';
                this.password = '';
                this.togglePasswordField = false;
                this.uploadBtnToggle();
                this.$refs.myVueDropzone.setupEventListeners();

                var AddMoreBtnComponentClass = Vue.extend(AddMoreButton);
                var addBtnInstance = new AddMoreBtnComponentClass({
                    propsData: {toggleAddMoreBtn: this.uploadBtnHideShow}
                });
                addBtnInstance.$mount();

            },
            fileError: function (file, message, xhr) {
                this.errorMessage = message.message;
                this.togglePasswordField = (message.return_code === 15 || message.return_code === 16);

                file.status = 'queued';
                this.$refs.myVueDropzone.removeEventListeners();
            },
            validateSubmitForm: function () {

                this.$validator
                    .validateAll()
                    .then((response) => {

                        if (!response) {
                            return;
                        }
                        this.$refs.myVueDropzone.processQueue();
                    })
                    .catch((e) => {
                        console.log(e);
                    });
            },
            isAnyInvalidFile: function () {
                return this.$refs.myVueDropzone && this.$refs.myVueDropzone.getRejectedFiles().length !== 0;
            },
            closeUploadModalPopUp: function () {
                this.uploadBtnHideShow = true;
                this.showThankYouIcon = false;
                this.errorMessage = '';
            },
            isAnyFileInQueue: function () {
                return this.$refs.myVueDropzone && this.$refs.myVueDropzone.getQueuedFiles().length !== 0;
            },
            uploadBtnToggle: function () {
                this.uploadBtnHideShow = !!(this.isAnyFileInQueue() && this.isAnyInvalidFile() === false);
            },
        },
        mounted: function () {
            $(this.$refs.uploadVueModalRef).on("hidden.bs.modal", this.closeUploadModalPopUp);
        },
        computed: {
            documentExtraParameter: function () {
                return {
                    user_id: this.customerDetails.USERID,
                    serial_no: this.getSerialNumber,
                    cust_no: this.specificFileDetails.APPL_CUST_NUMBER,
                    session_id: '',
                    type: this.getType,
                    doc_ref_no: this.specificFileDetails.DTL_SRNO,
                    doc_type: this.specificFileDetails.DOC_TYPE,
                    doc_status: 'UPLOAD',
                    file_name: '',
                    step_no: '3',
                    additional_info1: '',
                    additional_info2: this.password,
                    additional_info3: this.customerDetails.DIGITAL_DOC_SRNO,
                    document_remarks: '',
                    req_stage: this.customerDetails.REQ_STAGE,
                    created_by: this.customerDetails.USERID,
                    doc_source: 'LOCAL',
                };
            },
            getType: function () {
                return this.customerDetails.REQ_STAGE === 'LEAD'
                    ? 'DIGITAL_LEAD'
                    : ( this.customerDetails.REQ_STAGE === 'FILE' ? 'DIGITAL_FILE' : (this.customerDetails.REQ_STAGE === 'SERIAL' ? 'DIGITAL_SERIAL' : 'DIGITAL_DOCS' ) );
            },
            getSerialNumber: function () {
                return this.customerDetails.REQ_STAGE === 'LEAD'
                    ? this.customerDetails.LEAD_NO
                    : this.customerDetails.FILE_NO;
            },
            showUploadButton: function () {
                return this.uploadBtnHideShow;
            },
            showErrorMessage: function () {
                return !!this.errorMessage;
            },
            showPasswordSection: function () {
                return this.togglePasswordField;
            },
        }
    }
</script>