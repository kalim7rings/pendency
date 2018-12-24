<template>
    <nav class="navbar navbar-expand fixed-top be-top-header">
        <div class="container-fluid">
            <div class="be-navbar-header"><a class="navbar-brand"></a> </div>
            <div class="be-right-navbar">

                <ul v-show="showName" class="nav navbar-nav float-right be-icons-nav">
                    Welcome :  <strong> {{ customerName }} </strong>
                </ul>

                <ul v-show="showLogout" class="nav navbar-nav float-right be-user-nav">
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <span class="user-name-top"> {{avatarName}} </span>
                        </a>
                        <div role="menu" class="dropdown-menu">
                            <div class="user-info">
                                <div class="user-name">Hi, {{ customerName }} </div>
                                <div class="user-position online"></div>
                            </div>
                            <a :href="logout" class="dropdown-item"><span class="icon mdi mdi-power"></span> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

    export default {
        props : ['userinfo'],
        data : function () {
            return {
                customerName: '',
                avatarName : '',
                logout : route('logout')
            }
        },
        methods:{
            getAvatarName : function () {
            },
        },
        mounted() {
            this.customerName = !!this.userinfo ? this.userinfo.cust_name : '';

            if(this.customerName) {
                this.avatarName = this.customerName.substr(0,1);
            }
        },
        computed : {
            showName : function () {
               return !!this.customerName && !this.showLogout;
            },
            showLogout : function () {
               return this.userinfo && !!this.userinfo.doc_srno;
            }
        }
    }
</script>