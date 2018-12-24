<template>
    <div v-if="showHideAddMoreBtn" class="add-more-btn needsclick" :class="toggleClass" id="add-more-btn" ref="addMoreBtn" @click="openDropzone">
        <slot/>
    </div>
</template>

<script>
    export default {
        props:['dropzone','toggleAddMoreBtn'],
        data: function () {
            return {
                toggleClass : 'block',
            }
        },
        methods : {
            openDropzone : function() {
                this.dropzone.$el.click();
                //document.getElementsByClassName('dropzone')[0].click();
            },
        },
        computed : {
            showHideAddMoreBtn : function () {
                let addbtn = document.getElementsByClassName('add-more-btn');

                    if(addbtn.length)
                        this.toggleAddMoreBtn ? addbtn[0].style.display = 'block-inline' : addbtn[0].style.display = 'none';

                return this.toggleAddMoreBtn;
            },
        },
    }
</script>

<style>
    .add-more-btn {
        border-radius: 8px;
        padding: 28px 39px;
        margin-top: 6px;
        position: relative;
        display: inline-block;
        vertical-align: top;
        font-size: 38px;
        border: 1px dashed #b1b0b0;
        margin-left: 56px;
    }
</style>