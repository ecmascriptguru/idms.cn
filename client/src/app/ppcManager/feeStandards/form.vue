
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'JdPpcFeeStandardForm',

    /**
    * Component's local state
    */
    data() {
      return {
        feeStandard: {
          id: 0,
          district_id: 0,
          house_type_id: 0,
          property_management_fee: 1,
          water_fee: 1,
          electricity_fee: 1,
          parking_fee: 100,
          gas_fee: 0,
          heating_fee: 0,
          internet_fee: 0,
          custom_fee_1_type_id: null,
          custom_fee_1_name: null,
          custom_fee_1_rate: null,
          custom_fee_2_type_id: null,
          custom_fee_2_name: null,
          custom_fee_2_rate: null,
          custom_fee_3_type_id: null,
          custom_fee_3_name: null,
          custom_fee_3_rate: null,
        },
      }
    },

    /**
    * Fetch feeStandard when component is first mounted
    */
    mounted() {
      this.fetchDistricts()
      this.fetchHouseTypes()
      this.fetch()
      this.fetchCustomFeeTypes()
      this.setDistrictId()
    },

    updated() {
      // this.setDistrictId()
    },

    /**
    * Also fetch feeStandard every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * feeStandard id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        districts: state => state.PpcManager.Districts.full_list,
        houseTypes: state => state.Admin.HouseTypes.full_list,
        customFeeTypes: state => state.Admin.CustomFeeTypes.full_list,
      }),
      isEditing() {
        return this.feeStandard.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.feeStandard.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill feeStandard name'] })
          return false
        }
        return true
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      districtId() {
        return this.$route.query.dctId || 0
      },
    },

    methods: {
      ...mapActions(['districtsSetData', 'houseTypesSetData', 'customFeeTypesSetData', 'setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the feeStandard
      * from the server
      */
      fetch() {
        this.$refs.firstInput.focus()

        const id = this.$route.params.id
        /**
        * This same component is used for create
        * and update so we have to check if
        * ID is not undefined.
        */
        if (id !== undefined) {
          /**
          * Fetch the feeStandard from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`pca/feeStandards/${id}`).then((res) => {
            const { 
              id: _id,
              district_id,
              house_type_id,
              property_management_fee,
              water_fee,
              electricity_fee,
              parking_fee,
              gas_fee,
              heating_fee,
              internet_fee,
              custom_fee_1_type_id,
              custom_fee_1_name,
              custom_fee_1_rate,
              custom_fee_2_type_id,
              custom_fee_2_name,
              custom_fee_2_rate,
              custom_fee_3_type_id,
              custom_fee_3_name,
              custom_fee_3_rate,
            } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.feeStandard.id = _id
            this.feeStandard.district_id = district_id
            this.feeStandard.house_type_id = house_type_id
            this.feeStandard.property_management_fee = property_management_fee
            this.feeStandard.water_fee = water_fee
            this.feeStandard.electricity_fee = electricity_fee
            this.feeStandard.parking_fee = parking_fee
            this.feeStandard.gas_fee = gas_fee
            this.feeStandard.heating_fee = heating_fee
            this.feeStandard.internet_fee = internet_fee
            this.feeStandard.custom_fee_1_type_id = custom_fee_1_type_id
            this.feeStandard.custom_fee_1_name = custom_fee_1_name
            this.feeStandard.custom_fee_1_rate = custom_fee_1_rate
            this.feeStandard.custom_fee_2_type_id = custom_fee_2_type_id
            this.feeStandard.custom_fee_2_name = custom_fee_2_name
            this.feeStandard.custom_fee_2_rate = custom_fee_2_rate
            this.feeStandard.custom_fee_3_type_id = custom_fee_3_type_id
            this.feeStandard.custom_fee_3_name = custom_fee_3_name
            this.feeStandard.custom_fee_3_rate = custom_fee_3_rate
            this.setFetching({ fetching: false })
          })
        }
      },
      fetchDistricts() {
        if (!this.districts.length) {
          this.setFetching({ fetching: true })
          this.$http.get('pca/districts/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.districtsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setDistrictId()
          })
        }
      },
      fetchHouseTypes() {
        if (!this.houseTypes.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/houseTypes/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.houseTypesSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setHouseTypeId()
          })
        }
      },
      fetchCustomFeeTypes() {
        if (!this.customFeeTypes.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/customFeeTypes/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.customFeeTypesSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            // this.setHouseTypeId()
          })
        }
      },
      submit() {
        /**
        * Pre-conditions are met
        */
        if (this.isValid) {
          /**
          * Shows the global spinner
          */
          this.setFetching({ fetching: true })

          if (this.isEditing) {
            this.update()
          } else {
            this.save()
          }
        }
      },
      save() {
        this.$http.post('pca/feeStandards', this.feeStandard).then(() => {
          /**
          * This event will notify the world about
          * the feeStandard creation. In this case
          * the feeStandard main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('feeStandard.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New feeStandard was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`pca/feeStandards/${this.feeStandard.id}`, this.feeStandard).then(() => {
          /**
          * This event will notify the world about
          * the feeStandard creation. In this case
          * the feeStandard main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('feeStandard.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'FeeStandard was updated' })
          this.$router.push({ 
            name: 'ppcManager.feeStandards.index',
            query: { page: this.currentPage, dctId: this.districtId }
          })
        })
      },
      reset() {
        this.feeStandard.id = 0
        this.feeStandard.district_id = 0
        this.feeStandard.house_type_id = 0
        this.feeStandard.property_management_fee = 1
        this.feeStandard.water_fee = 1
        this.feeStandard.electricity_fee = 1
        this.feeStandard.parking_fee = 100
        this.feeStandard.gas_fee = 0
        this.feeStandard.heating_fee = 0
        this.feeStandard.internet_fee = 0
        this.feeStandard.custom_fee_1_type_id = null
        this.feeStandard.custom_fee_1_name = null
        this.feeStandard.custom_fee_1_rate = null
        this.feeStandard.custom_fee_2_type_id = null
        this.feeStandard.custom_fee_2_name = null
        this.feeStandard.custom_fee_2_rate = null
        this.feeStandard.custom_fee_3_type_id = null
        this.feeStandard.custom_fee_3_name = null
        this.feeStandard.custom_fee_3_rate = null
      },
      setDistrictId() {
        this.feeStandard.district_id = parseInt(this.$route.query.dctId || 0)
        $(`#district_id`).val(this.feeStandard.district_id)
      },
      setHouseTypeId() {
        $(`#house_type_id`).val(this.feeStandard.house_type_id)
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="district_id" class="control-label">社区</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select ref="firstInput" name="district_id" id="district_id" class="form-control" v-model="feeStandard.district_id">
            <option v-for="district in districts" :value="district.id" :selected="district.id == feeStandard.district_id">
              {{ district.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="house_type_id" class="control-label">房屋类型</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select name="house_type_id" id="house_type_id" class="form-control" v-model="feeStandard.house_type_id">
            <option v-for="houseType in houseTypes" :value="houseType.id">
              {{ houseType.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="property_management_fee" class="control-label">物业管理费（元/月/平米）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="property_management_fee" class="form-control" v-model="feeStandard.property_management_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="water_fee" class="control-label">水费（元/每立方）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="water_fee" class="form-control" v-model="feeStandard.water_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="electricity_fee" class="control-label">电费（元/度）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="electricity_fee" class="form-control" v-model="feeStandard.electricity_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="parking_fee" class="control-label">停车费（元/月/车）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="parking_fee" class="form-control" v-model="feeStandard.parking_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="gas_fee" class="control-label">煤气费（元/立方）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="gas_fee" class="form-control" v-model="feeStandard.gas_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="heating_fee" class="control-label">暖气费（元/月/平米）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="heating_fee" class="form-control" v-model="feeStandard.heating_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="internet_fee" class="control-label">宽带费（元/月）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="internet_fee" class="form-control" v-model="feeStandard.internet_fee">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_1_name" class="control-label">其他费用设置一（名称）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" id="custom_fee_1_name" class="form-control" v-model="feeStandard.custom_fee_1_name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_1_type_id" class="control-label">其他费用设置一（类别）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select type="number" id="custom_fee_1_type_id" class="form-control" v-model="feeStandard.custom_fee_1_type_id">
            <!-- <option value=""></option> -->
            <option v-for="customFeeType in customFeeTypes" :value="customFeeType.id">
              {{ customFeeType.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_1_rate" class="control-label">其他费用设置一（费率）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="custom_fee_1_rate" class="form-control" v-model="feeStandard.custom_fee_1_rate">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_2_name" class="control-label">其他费用设置一（名称）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" id="custom_fee_2_name" class="form-control" v-model="feeStandard.custom_fee_2_name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_2_type_id" class="control-label">其他费用设置一（类别）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select type="number" id="custom_fee_2_type_id" class="form-control" v-model="feeStandard.custom_fee_2_type_id">
            <!-- <option value=""></option> -->
            <option v-for="customFeeType in customFeeTypes" :value="customFeeType.id">
              {{ customFeeType.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_2_rate" class="control-label">其他费用设置一（费率）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="custom_fee_2_rate" class="form-control" v-model="feeStandard.custom_fee_2_rate">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_3_name" class="control-label">其他费用设置一（名称）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" id="custom_fee_3_name" class="form-control" v-model="feeStandard.custom_fee_3_name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_3_type_id" class="control-label">其他费用设置一（类别）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select type="number" id="custom_fee_3_type_id" class="form-control" v-model="feeStandard.custom_fee_3_type_id">
            <!-- <option value=""></option> -->
            <option v-for="customFeeType in customFeeTypes" :value="customFeeType.id">
              {{ customFeeType.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="custom_fee_3_rate" class="control-label">其他费用设置一（费率）</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="custom_fee_3_rate" class="form-control" v-model="feeStandard.custom_fee_3_rate">
        </div>
      </div>
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
