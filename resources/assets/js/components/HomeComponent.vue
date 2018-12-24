<template>
    <div class="container-fluid main_content">
        <div class="row">
            <div class="col-12 col-lg-12 col-md-9 main_right_box">
                <div class="card_inner">
                    <div class="inner_title">
                        Upload Documents for a faster loan approval for your HDFC Loan  Ac No : {{accNoMask}}
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 text-center">
                            <div class="font-400 font18 margin-top30"> Enter the OTP received on {{mobileNoMask}} or  {{ emailMask }} </div>
                        </div>
                    </div>

                    <h5 class="msg text-danger text-center margin-top20 margin-bottom20 font14">{{msg}}</h5>

                    <div class="alert alert-danger" v-show="errors.any()">
                        <div v-for="err in errors.all()">
                            <li> {{ err }} </li>
                        </div>
                    </div>

                    <form @submit.prevent="validateOtp" id="frmOTP" name="frmOTP" method="post">
                        <div class="row margin-top10 text-center">
                            <div class="col-lg-2 col-sm-6 col-md-6">
                                <input type="password" v-validate="'required|numeric|min:4|max:6'" name="otp" v-model="otp" class="form-control">
                                <span v-show="errors.has('otp')" class="help text-danger">{{ errors.first('otp') }}</span>
                            </div>
                        </div>
                        <div class="row margin-top30">
                            <div class="col-lg-12 text-center">
                                <button class="btn btn-primary btn-sbmit" :disabled="errors.any()" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>

                    <div class="row margin-top30 margin-bottom30">
                        <div class="col-lg-12 text-center resend-div text-danger">
                            <div class="resend-mgs mb-3"></div>
                            <button type="button" class="btn btn-outline-primary btn-resend">Resend OTP</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props : ['user'],
        data : function () {
            return {
                    otp : '',
                    msg : '',
            }
        },
        methods:{
            numberMask : function (no) {
                return 'x'.repeat(no.length-4)+''+no.substr(-4);
            },
            validateOtp : function () {
                this.$validator
                    .validateAll()
                    .then( (response) => {

                        if(response)
                        {
                             axios.post(route('api.otp.validate'), {otp:this.otp})
                                 .then( (resp) => {

                                     if(resp.data.status)
                                     {
                                         this.msg = '';
                                         window.location = route('dashboard');
                                     }
                                 })
                                 .catch( (e)  => {
                                     this.msg = e.response.data.message;
                                 });
                        }

                    })
                    .catch((e) => {
                        console.log('error');
                        console.log(e);
                    });
            },
        },
        mounted() {
            console.log(this.user);
            console.log('Component mounted.');
            console.log(this.email);

        },
        computed : {
            emailMask : function () {
               var email = this.user.email_id;
                   email = email.split('@');

               return `${email[0].substr(0,3)} ${'x'.repeat(email[0].length-3)}@${email[1]}`;
            },
            mobileNoMask : function () {
                return this.numberMask(this.user.mobile_no);
            },
            accNoMask : function () {
              return this.numberMask(this.user.file_no);
            },
        },
        created () {
            console.log('Component created called.');
        }
    }
</script>