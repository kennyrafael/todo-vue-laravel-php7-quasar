<template>
  <q-page class="flex flex-center">
    <q-splitter v-model="splitterModel" style="height: 450px; width: 900px">
      <template v-slot:before>
        <div class="q-pa-md">
          <q-date v-model="date" :events="events" event-color="orange" />
        </div>
      </template>

      <template v-slot:after>
        <q-tab-panels
          v-model="dateTab"
          animated
          transition-prev="jump-up"
          transition-next="jump-up"
        >
          <q-tab-panel v-for="event in events" v-bind:key="event" :name="event">
            <div class="text-h4 q-mb-md">
              {{ event }}
              <q-btn class="gt-xs" size="12px" dense round icon="add" v-on:click="create = true"/>
            </div>
            <q-list>
              <template v-for="(todo, index) in filteredTodos">
                <q-item v-bind:key="todo.schedule + index">
                  <q-item-section>
                    <q-item-label>{{ todo.title }}</q-item-label>
                    <q-item-label caption lines="2">{{
                      todo.description
                    }}</q-item-label>
                  </q-item-section>

                  <q-item-section center side>
                    <div class="text-grey-8 q-gutter-xs">
                      <q-btn
                        class="gt-xs"
                        size="12px"
                        flat
                        dense
                        round
                        icon="delete"
                        @click="deleteTodo(todo.id)"
                      />
                      <q-btn
                        class="gt-xs"
                        size="12px"
                        flat
                        dense
                        round
                        icon="edit"
                        @click="edittingTodo = todo"
                      />
                    </div>
                  </q-item-section>
                </q-item>
                <q-separator v-bind:key="index + todo.schedule" spaced inset />
              </template>
            </q-list>
          </q-tab-panel>
          <q-tab-panel name="empty">
            <div class="text-h4 q-mb-md">
              {{ date }}
              <q-btn class="gt-xs" size="12px" dense round icon="add" v-on:click="create = true"/></div>
            <q-list>
              <template>
                <q-item>
                  <q-item-section>
                    <q-item-label>Nothing ToDo here!</q-item-label>
                  </q-item-section>
                </q-item>
                <q-separator spaced inset />
              </template>
            </q-list>
          </q-tab-panel>
        </q-tab-panels>
      </template>
    </q-splitter>

    <q-dialog v-model="create">
      <Create @close="create = false" @created="todoCreated" :date="date"></Create>
    </q-dialog>

    <q-dialog v-model="edit">
      <Edit @close="edit = false" @edited="todoEdited" v-bind:todo="edittingTodo"></Edit>
    </q-dialog>

  </q-page>
</template>

<script>
import moment from 'moment-timezone'
import { RepositoryFactory } from './../repositories/RepositoryFactory'
const TodoRepository = RepositoryFactory.get('todos')

import Edit from '../components/Edit'
import Create from '../components/Create'

export default {
  name: 'PageIndex',
  components: {
    Edit,
    Create
  },
  data () {
    return {
      edit: false,
      edittingTodo: null,
      create: false,
      splitterModel: 50,
      dateTab: '',
      date: moment().format('YYYY/MM/DD'),
      events: [],
      todos: []
    }
  },
  methods: {
    populate: async function () {
      const { data } = await TodoRepository.get()
      this.todos = data.data
      this.todos.map(todo => {
        if (!this.events.includes(todo.schedule)) {
          this.events.push(todo.schedule)
        }
      })
      this.checkDate()
    },
    checkDate: function () {
      const event = this.todos.filter(todo => todo.schedule === this.date)
      this.dateTab = event.length ? this.date : 'empty'
    },
    todoCreated: function (newTodo) {
      this.todos.push(newTodo)
      if (!this.events.includes(newTodo.schedule)) {
        this.events.push(newTodo.schedule)
      }
      this.checkDate()
      this.create = false
    },
    todoEdited: function (editedTodo) {
      this.todos = this.todos.map(todo => {
        if (todo.id === editedTodo.id) {
          todo = editedTodo
        }
        return todo
      })
      this.checkDate()
      this.edit = false
    },
    deleteTodo: async function (id) {
      try {
        await TodoRepository.delete(id)
        this.events = []
        this.todos = this.todos.filter(todo => todo.id !== id)
        this.todos.map(todo => {
          if (!this.events.includes(todo.schedule)) {
            this.events.push(todo.schedule)
          }
        })
        this.checkDate()
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Todo deleted with success!'
        })
      } catch (e) {
        this.$q.notify({
          color: 'red-4',
          textColor: 'white',
          icon: 'warning',
          message: 'Something went wrong. =/'
        })
      }
    }
  },
  watch: {
    date: function () {
      this.checkDate()
    },
    edittingTodo: function (val) {
      this.edit = val !== null
    }
  },
  computed: {
    filteredTodos: function () {
      return this.todos.filter(todo => todo.schedule === this.date)
    }
  },
  mounted: function () {
    this.populate()
  }
}
</script>
