<script>
    import { mapActions, mapState } from 'vuex'
    export default {
        name: "JdOpShopForm",

        /**
         * Component's local state
         */
        data() {
            return {
                OperatingCompany: {
                    shop: 1,
                }
            }
        },

        mounted() {
            //
        },

        watch: {
            $route: 'fetch',
        },

        computed: {
            isValid() {
                this.resetMessages();
                if (true) {
                    return true;
                }
                return true;
            },
        },

        methods: {
            ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

            fetch() {
                this.$refs.firstInput.focus()

                this.setFetching({ fetching: true })
                this.$http.get(`oca/org`).then((res) => {
                    const { shop } = res.data.data // http://wesbos.com/destructuring-renaming/
                    this.PropertyCompany.shop = shop
                })
            },

            submit() {
                if (this.isValid) {
                    swal({
                        title: 'Are you sure?',
                        text: `Property Company will be updated.`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, do it!',
                        closeOnConfirm: false,
                    }, () => this.update()) // callback executed when OK is pressed
                    
                }
            },

            update() {
                this.$http.put(`oca/org`, this.OperatingCompany).then(() => {
                    /**
                     * This event will notify the world about
                    * the op creation. In this case
                    * the Op main component will intercept
                    * the event and refresh the list.
                    */
                    this.$bus.$emit('operatingCompany.updated')

                    /**
                     * Hides the global spinner
                    */
                    this.setFetching({ fetching: false })

                    /**
                     * Sets the global feedback message
                    */
                    this.setMessage({ type: 'success', message: 'Operating Company was updated' })
                })
            },

            reset() {
                this.OperatingCompany.shop = ''
            },
        }
    }
</script>


<template>
    <div class="content-wrapper" style="padding-top:100px;">
        <form @submit.prevent="submit" class="well col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="shop" class="control-label">商铺入住审核权限</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <select ref="firstInput" name="shop" id="shop" class="form-control" v-model="OperatingCompany.shop">
                            <option value="1">允许小区管理员审核</option>
                            <option value="0">不允许小区管理员审核</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
        </form>
    </div>
</template>
