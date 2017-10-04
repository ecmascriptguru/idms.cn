<script>
    import { mapActions, mapState } from 'vuex'
    export default {
        name: "JdOpParameterForm",

        /**
         * Component's local state
         */
        data() {
            return {
                Parameter: {
                    video_intercom_call_waiting_time: 30,
                    adv_refresh_waiting_time: 60,
                    device_connection_waiting_time: 10,
                    direct_phone_call_waiting_time_limit: 60,
                    password_input_waiting_time: 20,
                    unit_number_length: 4,
                    floor_length_number: 2,
                    floor_length: 2,
                    media_sound_volumn: 3,
                    call_sound_volumn: 8,
                    dial_ring_tones: 5,
                    system_sound_volumn: 2,
                    video_quality: 4,
                    video_auto_adaption_network: 1,
                }
            }
        },

        mounted() {
            this.fetch()
            if (jQuery.AdminLTE.layout) {
                jQuery.AdminLTE.layout.fix()
            } else {
                jQuery(document).ready(() => {
                    jQuery.AdminLTE.layout.fix()
                })
            }
        },

        updated() {
            if (jQuery.AdminLTE.layout) {
                jQuery.AdminLTE.layout.fix()
            } else {
                jQuery(document).ready(() => {
                    jQuery.AdminLTE.layout.fix()
                })
            }
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
            ...mapActions(['setFetching', 'resetMessages', 'setMessage', 'parameterSetData']),

            fetch() {
                this.$refs.firstInput.focus()

                this.setFetching({ fetching: true })
                this.$http.get(`oca/parameter/1000`).then((res) => {
                    let { data } = res.data
                    this.Parameter = data
                    this.parameterSetData({
                        parameter: data,
                    })
                })
            },

            submit() {
                if (this.isValid) {
                    swal({
                        title: 'Are you sure?',
                        text: `Device parameters will be updated.`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, do it!',
                        closeOnConfirm: false,
                    }, () => this.update()) // callback executed when OK is pressed
                    
                }
            },

            update() {
                this.$http.put(`oca/parameter/1000`, this.Parameter).then(() => {
                    /**
                     * This event will notify the world about
                    * the op creation. In this case
                    * the Op main component will intercept
                    * the event and refresh the list.
                    */
                    this.$bus.$emit('parameter.updated')

                    /**
                     * Hides the global spinner
                    */
                    this.setFetching({ fetching: false })

                    /**
                     * Sets the global feedback message
                    */
                    this.setMessage({ type: 'success', message: 'Parameter was updated' })
                    swal('Done!', 'Parameters updated.', 'success')
                })
            },

            reset() {
                this.Parameter.video_intercom_call_waiting_time = 30
                this.Parameter.adv_refresh_waiting_time = 60
                this.Parameter.device_connection_waiting_time = 10
                this.Parameter.direct_phone_call_waiting_time_limit = 60
                this.Parameter.password_input_waiting_time = 20
                this.Parameter.unit_number_length = 4
                this.Parameter.floor_length_number = 2
                this.Parameter.floor_length = 2
                this.Parameter.media_sound_volumn = 3
                this.Parameter.call_sound_volumn = 8
                this.Parameter.dial_ring_tones = 5
                this.Parameter.system_sound_volumn = 2
                this.Parameter.video_quality = 4
                this.Parameter.video_auto_adaption_network = 1
            },
        }
    }
</script>


<template>
    <div class="content-wrapper">
        <div class="row" style="padding-top:40px;">
            <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                <form @submit.prevent="submit" class="well">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="video_intercom_call_waiting_time" class="control-label" >可视对讲拨号等待时间（秒）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="20" max="60" id="video_intercom_call_waiting_time" class="form-control" name="video_intercom_call_waiting_time" v-model="Parameter.video_intercom_call_waiting_time" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="adv_refresh_waiting_time" class="control-label" >广告刷新等待时间（分钟）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="20" max="90" id="adv_refresh_waiting_time" class="form-control" name="adv_refresh_waiting_time" v-model="Parameter.adv_refresh_waiting_time" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="device_connection_waiting_time" class="control-label" >设备连接报告等待时间（分钟）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="3" max="30" id="device_connection_waiting_time" class="form-control" name="device_connection_waiting_time" v-model="Parameter.device_connection_waiting_time" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="direct_phone_call_waiting_time_limit" class="control-label" >直拨电话通话时间限制（秒）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="20" max="80" id="direct_phone_call_waiting_time_limit" class="form-control" name="direct_phone_call_waiting_time_limit" v-model="Parameter.direct_phone_call_waiting_time_limit" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="password_input_waiting_time" class="control-label" >密码状态停留时间（秒）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="10" max="30" id="password_input_waiting_time" class="form-control" name="password_input_waiting_time" v-model="Parameter.password_input_waiting_time" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="unit_number_length" class="control-label" >单元号码长度（位）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="10" id="unit_number_length" class="form-control" name="unit_number_length" v-model="Parameter.unit_number_length" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="floor_length_number" class="control-label" > 楼栋号码长度（位）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="6" id="floor_length_number" class="form-control" name="floor_length_number" v-model="Parameter.floor_length_number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="floor_length" class="control-label" > 楼层长度（位）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="6" id="floor_length" class="form-control" name="floor_length" v-model="Parameter.floor_length" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="media_sound_volumn" class="control-label" > 媒体声音（最大是10）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="10" id="media_sound_volumn" class="form-control" name="media_sound_volumn" v-model="Parameter.media_sound_volumn" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="call_sound_volumn" class="control-label" >通话声音（最大是10）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="10" id="call_sound_volumn" class="form-control" name="call_sound_volumn" v-model="Parameter.call_sound_volumn" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="dial_ring_tones" class="control-label" >拨号铃声（最大是10）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="10" id="dial_ring_tones" class="form-control" name="dial_ring_tones" v-model="Parameter.dial_ring_tones" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="system_sound_volumn" class="control-label" >系统声音（最大是10）</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <input type="number" min="1" max="10" id="system_sound_volumn" class="form-control" name="system_sound_volumn" v-model="Parameter.system_sound_volumn" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="video_quality" class="control-label" >视频质量</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <select type="number" min="20" max="60" id="video_quality" class="form-control" name="video_quality" v-model="Parameter.video_quality">
                                    <option value="0">标准</option>
                                    <option value="1">流畅</option>
                                    <option value="2">高清</option>
                                    <option value="3">720P</option>
                                    <option value="4">1080P</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                <label for="video_auto_adaption_network" class="control-label">视频自动适应网络</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                <select ref="firstInput" name="video_auto_adaption_network" id="video_auto_adaption_network" class="form-control" v-model="Parameter.video_auto_adaption_network">
                                    <option value="1">打开</option>
                                    <option value="0">关闭</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
