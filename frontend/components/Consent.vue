<template>
  <v-layout>
    <v-flex>
      <v-card>
        <v-form>
          <v-container fluid>
            <v-layout justify-center row wrap>
              <v-flex xs6 sm8>
                <h5 class="display-1">Consent</h5>
              </v-flex>
            </v-layout>
            
            <v-layout justify-center row wrap>
              <v-flex xs6 sm8>
                By clicking the button below, I hereby give my consent to the collection and processing of my personal information 
                to Isuzu Philippines Corporation (IPC), its authorized representatives, dealerships and agents, 
                in full compliance with the Data Privacy Act of 2012 of the Republic of the Philippines. 
                Please read full data privacy policy at
                <a href="https://www.isuzuphil.com/about/privacy-policy">https://www.isuzuphil.com/about/privacy-policy</a>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>

        <v-layout justify-center row>
         
            <v-btn 
            @click="agree"
       
            color="success"
        
            large
            >
              <i :class="`fa fa-check`"></i>&nbsp;
              Agree
            </v-btn>
           
            <!-- <v-btn 
            @click="proceed"
            :disabled="isAgree"
            color="red darken-1" 
            flat
            >
              Proceed &nbsp;
              <v-icon small>fa fa-arrow-circle-right</v-icon>
            </v-btn> -->
        </v-layout>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
import axios from 'axios'

export default {
  name: 'Consent',
  data () {
    return {
        isAgree : true,
         button_status: {
            color: '',
            icon: 'question-circle'
        }
    }
  },
  
  mounted () {

  },
  methods: {
   
  
    proceed () {
      this.$store.commit('request/NEXT_PAGE')
    },
    agree () {
        this.$store.commit('request/NEXT_PAGE')
        /* this.isAgree = !this.isAgree;

        if(!this.isAgree){
            this.button_status = Object.assign({}, this.button_status, {
                color: 'green white--text',
                icon: 'check'
                
            });
            this.$store.commit('request/NEXT_PAGE')
        }
        else {
            this.button_status = Object.assign({}, this.button_status, {
                color: '',
                icon: 'question-circle'
            });
        } */
        
    }
  }
}
</script>