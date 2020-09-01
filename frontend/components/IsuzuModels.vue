<template>
  <v-container grid-list-md text-xs-center>
  <v-layout row wrap>
     <v-flex xs12>
        <h3 class="raleway headline mb-10">
          Choose the Isuzu model for the requested training would you like to focus on
        </h3>
        <br/>
      </v-flex>

      <v-flex xs9>
         <div 
          v-for="(item, index) in unit_models"
          :key="item.unit_model_id"
          @click="unitModelPicked('unit_model_id', item.unit_model_id)"
          @mouseenter="hover(index)"
          @mouseleave="unhover(index)"
          class="raleway menu">
            <img 
            :id="`item-${index}`"
            :src="`${base_url}/public/images/unit_models/${item.image}`" 
            :alt="item.image">
            <p :class="`${form.unit_model_id == item.unit_model_id ? 'green--text' : ''}`">
              <i v-if="form.unit_model_id == item.unit_model_id" 
              class="fa fa-check-circle"
              ></i>
              {{ item.model_name }}
            </p>
          </div>
      </v-flex>
      <v-flex xs3>
          
          <v-layout justify-center row wrap>
             
                <v-select
                label="Emission standard"
                name="Emission standard"
                v-model="emission_standard_id"
                v-validate="'required'"
                :error-messages="errors.first('Emission standard')"
                 :items="emission_standard_types"
                item-text="name"
                item-value="emission_standard_id"
                outline
                ></v-select>
            
            </v-layout>

            <v-layout justify-center row wrap>
             
                <v-select
                label="Rear body"
                name="Rear body"
                v-model="body_type_id"
                v-validate="'required'"
                :error-messages="errors.first('Rear body')"
                :items="rear_body_types"
                item-text="name"
                item-value="body_type_id"
                outline
                ></v-select>
            
            </v-layout>

      </v-flex>
  

    <v-flex xs12 right>
       <v-card-actions>
        <v-btn 
          @click="back"
          >
            <v-icon small>fa fa-arrow-circle-left</v-icon>&nbsp;
            Back
          </v-btn>
           <v-spacer></v-spacer>
          <v-btn 
          @click="nextPage"
          :disabled="!this.form.unit_model_id || !this.form.emission_standard_id ? true : false"
          color="red darken-1"
          :dark="!this.form.unit_model_id || !this.form.emission_standard_id ? false : true"
          >
            Proceed &nbsp;
            <v-icon small>fa fa-arrow-circle-right</v-icon>
          </v-btn>
       </v-card-actions>
    </v-flex>
    
  </v-layout>
  </v-container>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
import axios from 'axios'
export default {
  name: 'IsuzuModels',
  data () {
    return {
      rear_body_types : [],
      emission_standard_types : []
    }
  },
  computed: {
    ...mapState('request', [
      'form',
      'unit_models'
    ]),
    ...mapGetters('request', [
      'getCurrentPage',
      'getSpecialTrainings'
    ]),
    emission_standard_id: {
      get () {
        return this.$store.state.request.form.emission_standard_id
      },
      set (val,text) {
        this.$store.commit('request/UPDATE_FORM', {key:'emission_standard_id',value:val})
      }
    },
    body_type_id: {
      get () {
        return this.$store.state.request.form.body_type_id
      },
      set (val,text) {
        this.$store.commit('request/UPDATE_FORM', {key:'body_type_id',value:val})
      }
    },
  },
  created () {
    this.displayImages ()
  },
  mounted () {
    this.fetchEmissionStandards();
    
  },
  methods: {
    hover (index) {
      document.querySelector('#item-' + index).classList.add('animated', 'pulse', 'faster')
    },
    unhover (index) {
      document.querySelector('#item-' + index).classList.remove('animated', 'pulse', 'faster')
    },
    unitModelPicked (field, value) {
      this.fetchBodyTypes(value);
      this.$store.commit('request/UPDATE_FORM', {key:field,value:value})
    },
    displayImages () {
      this.$store.dispatch('request/fetchUnitModels')
    },
   
    nextPage () {
      this.$store.commit('request/NEXT_PAGE')

      if (this.getCurrentPage >= 5) {
        this.$store.dispatch('request/setSpecialTrainings')
      }
    },
    back () {
      this.$store.commit('request/BACK_PAGE')
    },

    fetchEmissionStandards () {
      var self = this;
      axios.get(`${this.api_url}emission_standards/get`)
      .then(({data}) => {
        this.emission_standard_types = data;
      })
      .catch((error) => {
       // self.fetchEmissionStandards();
         console.log(error);
      });
    },
    fetchBodyTypes (unit_model_id) {
      var self = this;
      axios.get(`${this.api_url}body_types/get/${unit_model_id}`)
      .then(({data}) => {
        this.rear_body_types = data;
      })
      .catch((error) => {
        //self.fetchBodyTypes();
        console.log(error);
      });
    },
  }
}
</script>

<style scoped>
.raleway {
  color: #636b6f;
  font-family: 'Raleway', sans-serif;
  font-weight: 600;
}
.menu {
  width:170px; 
  text-align:center; 
  float:left; 
  position:relative
}
.menu_label {
  color:#FFFFFF; 
  float:left; 
  position:absolute; 
  top:20px; 
  left:150px;
} 
.swal-button--confirm {
  background-color: #F44336;
}
.menu:hover {
  cursor: pointer;
}
</style>
