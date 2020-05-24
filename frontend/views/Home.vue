<template>
  <div>
    <RequestorTypeDialog v-if="false"></RequestorTypeDialog>
    <Covid19 v-if="true"></Covid19>
    <v-stepper 
    v-if="this.$store.state.request.form.requestorType" 
    :value="current_page" 
    non-linear
    >
      <v-stepper-header>
        <template
        v-for="(step, index) in getFormSteppers"
        >
          <v-stepper-step 
          :key="step.step"
          :complete="current_page > step.step" 
          :step="`${index+1}`" 
          edit-icon="$vuetify.icons.complete"
          color="red"
          >
            {{ step.step_name }}
          </v-stepper-step>

          <v-divider
            v-if="!index+1"
            :key="`div-${index+1}`"
            vertical
            inset
          ></v-divider>
        </template>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content
        v-for="(step, index) in getFormSteppers" 
        :key="index" 
        :step="step.step"
        >
          <component :is="step.component"></component>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
import RequestorTypeDialog from '../dialogs/RequestorTypeDialog'
import Covid19 from '../dialogs/Covid19'
import DealerForm from '../components/DealerForm'
import CustomerForm from '../components/CustomerForm'
import TrainingForm from '../components/TrainingForm'
import Programs from '../components/Programs'
import IsuzuModels from '../components/IsuzuModels'
import Submit from '../components/Submit'
import Consent from '../components/Consent'

export default {
  name: 'home',
  components: {
    RequestorTypeDialog, 
    Covid19,
    Consent,
    DealerForm, 
    CustomerForm, 
    TrainingForm, 
    Programs, 
    IsuzuModels, 
    Submit
  },
  data() {
    return {
      dialog: false,
      photo_gallery: false,
      drawer: true,
      participants: {},
      
      unit_models: [],
      dealers: [],
      training_programs: [],
      images: '',
      selected_unit: 0,
      didNotReadYet: true,
      disabled_dates: [],
      special_trainings: [],
    }
  },
  computed: {
    ...mapGetters('request', ['getFormSteppers', 'getRequestor']),
    ...mapState('request', ['current_page']),
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
</style>
