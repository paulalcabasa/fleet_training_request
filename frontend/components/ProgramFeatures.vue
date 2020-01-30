<template>
  <v-dialog
  v-model="features.isFeatureOpen"
  width="500"
  persistent
  >
    <v-card>
      <v-card-title
      class="headline grey lighten-2"
      primary-title
      >
        Topic Outline
      </v-card-title>

      <v-card-text>
        <v-data-table
        hide-headers
        hide-actions
        :items="items"
        >
          <template 
          v-slot:items="props"
          >
            <td>
              <ion-icon name="checkmark-circle-outline"></ion-icon>&nbsp;
              {{ props.item.feature }}
            </td>
          </template>
        </v-data-table>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
        @click="hideFeatures"
        color="red"
        small
        dark
        >
          <i class="fa fa-times"></i>&nbsp;
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'ProgramFeatures',
  props: ['features'],
  computed: {
    items () {
      return this.features.features
    }
  },
  methods: {
    hideFeatures () {
      this.$emit('hideFeatures')
    }
  }
}
</script>

<style scoped>
.card-title {
  min-height: 80px;
}
ion-icon {
  font-size: 14px;
  color: green;
}
</style>