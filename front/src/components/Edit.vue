<template>
  <q-card style="width: 350px" class="q-px-sm q-pb-md">
    <q-form @submit="onSubmit" @reset="close">
      <q-card-section>
        <div class="text-h6">Edit ToDo on: {{todo.date}}</div>
      </q-card-section>

      <q-card-section>
        <q-input
          outlined
          v-model="todo.title"
          label="Title"
          lazy-rules
          :rules="[val => (val && val.length > 0) || 'Please type something']"
        />

        <q-input
          outlined
          type="textarea"
          v-model="todo.description"
          label="Description"
          lazy-rules
          :rules="[
            val => (val !== null && val !== '') || 'Please type a description'
          ]"
        />
      </q-card-section>
      <q-card-actions align="right">
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
  name: 'Edit',
  props: {
    todo: Object
  },
  data () {
    return {}
  },
  methods: {
    async onSubmit () {
      const { data } = await TodoRepository.update(this.todo.id, this.todo)

      try {
        this.$emit('edited', data.data)

        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Todo edited with success!'
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
