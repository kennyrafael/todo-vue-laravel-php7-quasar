<template>
  <q-card style="width: 350px" class="q-px-sm q-pb-md">
    <q-form @submit="onSubmit" @reset="close">
      <q-card-section>
        <div class="text-h6">New ToDo on: {{ date }}</div>
      </q-card-section>

      <q-card-section>
        <q-input
          outlined
          v-model="title"
          label="Title"
          lazy-rules
          :rules="[val => (val && val.length > 0) || 'Please type something']"
        />

        <q-input
          outlined
          type="textarea"
          v-model="description"
          label="Description"
          lazy-rules
          :rules="[
            val => (val !== null && val !== '') || 'Please type a description'
          ]"
        />
      </q-card-section>
      <q-card-actions align="around">
        <q-btn label="Submit" type="submit" color="primary" />
        <q-btn
          label="Close"
          type="reset"
          color="primary"
          flat
          class="q-ml-sm"
        />
      </q-card-actions>
    </q-form>
  </q-card>
</template>

<script>
import { RepositoryFactory } from './../repositories/RepositoryFactory'
const TodoRepository = RepositoryFactory.get('todos')

export default {
  name: 'Create',
  props: {
    date: String
  },
  data () {
    return {
      title: '',
      description: ''
    }
  },
  methods: {
    async onSubmit () {
      const newTodo = {
        title: this.title,
        description: this.description,
        schedule: this.date
      }

      const { data } = await TodoRepository.create(newTodo)

      try {
        this.$emit('created', data.data)

        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Todo created with success!'
        })
      } catch (e) {
        this.$q.notify({
          color: 'red-4',
          textColor: 'white',
          icon: 'warning',
          message: 'Something went wrong. =/'
        })
      }
    },

    close () {
      this.$emit('close')
    }
  }
}
</script>
