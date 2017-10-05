
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'JdDctFlat',

    /**
    * Component's local state
    */
    data() {
      return {
        flat: {
          id: 0,
          building_id: 0,
          house_type_id: 0,
          name: '',
          number: '',
          area: '',
          owner_1_name: null,
          owner_1_document_type: null,
          owner_1_document_number: null,
          owner_1_phone: null,
          owner_2_name: null,
          owner_2_document_type: null,
          owner_2_document_number: null,
          owner_2_phone: null,
        },
      }
    },

    /**
    * Fetch flat when component is first mounted
    */
    mounted() {
      this.fetchBuildings()
      this.fetchHouseTypes()
      this.fetch()
      this.fetchCustomFeeTypes()
      this.setBuildingId()
    },

    updated() {
      // this.setBuildingId()
    },

    /**
    * Also fetch flat every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * flat id if the current actions
    * is editing instead of creating.
    */
    computed: {
      ...mapState({
        buildings: state => state.DctManager.Buildings.full_list,
        houseTypes: state => state.Admin.HouseTypes.full_list,
        customFeeTypes: state => state.Admin.CustomFeeTypes.full_list,
      }),
      isEditing() {
        return this.flat.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.flat.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill flat name'] })
          return false
        }
        return true
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      buildingId() {
        return this.$route.query.bdId || 0
      },
    },

    methods: {
      ...mapActions(['buildingsSetData', 'houseTypesSetData', 'customFeeTypesSetData', 'setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the flat
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
          * Fetch the flat from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`dct/flats/${id}`).then((res) => {
            const { 
              id: _id,
              building_id,
              house_type_id,
              name,
              number,
              area,
              owner_1_name,
              owner_1_document_type,
              owner_1_document_number,
              owner_1_phone,
              owner_2_name,
              owner_2_document_type,
              owner_2_document_number,
              owner_2_phone,
            } = res.data.data // http://wesbos.com/destructuring-renaming/

            this.flat.id = _id
            this.flat.building_id = building_id
            this.flat.house_type_id = house_type_id
            this.flat.name = name
            this.flat.number = number
            this.flat.area = area
            this.flat.owner_1_name = owner_1_name
            this.flat.owner_1_document_type = owner_1_document_type
            this.flat.owner_1_document_number = owner_1_document_number
            this.flat.owner_1_phone = owner_1_phone
            this.flat.owner_2_name = owner_2_name
            this.flat.owner_2_document_type = owner_2_document_type
            this.flat.owner_2_document_number = owner_2_document_number
            this.flat.owner_2_phone = owner_2_phone
            this.setFetching({ fetching: false })
          })
        }
      },
      fetchBuildings() {
        if (!this.buildings.length) {
          this.setFetching({ fetching: true })
          this.$http.get('dct/buildings/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.buildingsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setBuildingId()
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
        this.$http.post('dct/flats', this.flat).then(() => {
          /**
          * This event will notify the world about
          * the flat creation. In this case
          * the flat main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('flat.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New flat was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`dct/flats/${this.flat.id}`, this.flat).then(() => {
          /**
          * This event will notify the world about
          * the flat creation. In this case
          * the flat main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('flat.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Flat was updated' })
          this.$router.push({ 
            name: 'dctManager.flats.index',
            query: { page: this.currentPage, bdId: this.buildingId }
          })
        })
      },
      reset() {
        this.flat.id = 0
        this.flat.building_id = 0
        this.flat.house_type_id = 0
        this.flat.name = ''
        this.flat.number = ''
        this.flat.area = ''
        this.flat.owner_1_name = null
        this.flat.owner_1_document_type = null
        this.flat.owner_1_document_number = null
        this.flat.owner_1_phone = null
        this.flat.owner_2_name = null
        this.flat.owner_2_document_type = null
        this.flat.owner_2_document_number = null
        this.flat.owner_2_phone = null
      },
      setBuildingId() {
        this.flat.building_id = parseInt(this.$route.query.bdId || 0)
        $(`#building_id`).val(this.flat.building_id)
      },
      setHouseTypeId() {
        $(`#house_type_id`).val(this.flat.house_type_id)
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="building_id" class="control-label">楼栋编号</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <select ref="firstInput" name="building_id" id="building_id" class="form-control" v-model="flat.building_id">
            <option v-for="building in buildings" :value="building.id" :selected="building.id == flat.building_id">
              {{ building.name }}
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
          <select name="house_type_id" id="house_type_id" class="form-control" v-model="flat.house_type_id">
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
          <label for="number" class="control-label">房屋编号</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="number" class="form-control" v-model="flat.number">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="name" class="control-label">房屋名称</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="name" class="form-control" v-model="flat.name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="area" class="control-label">房屋面积(㎡)</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="number" step="0.1" id="area" class="form-control" v-model="flat.area">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_1_name" class="control-label">业主一姓名</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" id="owner_1_name" class="form-control" v-model="flat.owner_1_name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_1_document_type" class="control-label">业主一证件类型</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="owner_1_document_type" class="form-control" v-model="flat.owner_1_document_type">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_1_document_number" class="control-label">业主一证件号码</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="owner_1_document_number" class="form-control" v-model="flat.owner_1_document_number">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_1_phone" class="control-label">业主一联系电话</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="owner_1_phone" class="form-control" v-model="flat.owner_1_phone">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_2_name" class="control-label">业主二姓名</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" id="owner_2_name" class="form-control" v-model="flat.owner_2_name">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_2_document_type" class="control-label">业主二证件类型</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="owner_2_document_type" class="form-control" v-model="flat.owner_2_document_type">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_2_document_number" class="control-label">业主二证件号码</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="owner_2_document_number" class="form-control" v-model="flat.owner_2_document_number">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-2 col-md-5 col-md-offset-1 col-sm-6 col-xs-8">
          <label for="owner_2_phone" class="control-label">业主二联系电话</label>
        </div>
        <div class="col-lg-3 col-lg-offset-1 col-md-5 col-md-6 col-sm-5 col-xs-4">
          <input type="text" step="0.1" id="owner_2_phone" class="form-control" v-model="flat.owner_2_phone">
        </div>
      </div>
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
