<template>
  <div class="q-pa-md">
    <div class="row q-col-gutter-md q-mb-md">
      <div class="col-md-4">
        <q-input
            v-model="filter.dateFilter.from"
            type="datetime-local"
            label="От"
            dense
            clearable
            :disable="loading"
        />
      </div>
      <div class="col-md-4">
        <q-input
            v-model="filter.dateFilter.to"
            type="datetime-local"
            label="До"
            dense
            clearable
            :disable="loading"
        />
      </div>
      <div class="col-md-4">
        <q-select
            option-label="name"
            option-value="id"
            emit-value
            map-options
            v-model="filter.user_id"
            :options="users"
            label="Пользователь"
            clearable
            dense
            :loading="loading"
        />
      </div>
      <div class="col-md-4">
        <q-select
            option-label="title"
            option-value="id"
            emit-value
            map-options
            v-model="filter.type"
            :options="eventTypes"
            label="Тип события"
            clearable
            dense
            :loading="loading"
        />
      </div>
      <div class="col-md-8 flex justify-end">
        <q-btn
            class="q-px-md"
            dense
            outline
            color="primary"
            label="Поиск"
            :disable="loading"
            @click="onApplyForm"
        />
      </div>
    </div>
    <q-table
        ref="tableRef"
        binary-state-sort
        :rows="rows"
        :columns="tableColumns"
        :pagination="pagination"
        v-model:pagination="pagination"
        :loading="loading"
        row-key="id"
        no-data-label="Нет данных"
        loading-label="Подождите..."
        @request="onRequest"
        :rows-per-page-options="[]"
    >
      <template v-slot:loading>
        <q-inner-loading showing color="primary"/>
      </template>
    </q-table>
  </div>
</template>

<script>
import {api} from "../server";
import {onMounted, ref} from "vue";

const tableColumns = [
  {
    name: 'updated',
    required: false,
    label: 'Дата-время',
    align: 'left',
    field: 'updated',
    sortable: true
  },
  {
    name: 'employee_name',
    required: false,
    label: 'Пользователь',
    align: 'left',
    field: 'employee_name',
    sortable: true
  },
  {
    name: 'event',
    required: false,
    label: 'Событие',
    align: 'left',
    field: 'event',
    sortable: false
  },
  {
    name: 'details',
    required: false,
    label: 'Информация',
    align: 'left',
    field: 'details'
  }
];
export default {
  setup() {
    const tableRef = ref()
    const eventTypes = ref([])
    const users = ref([])
    const rows = ref([])
    const loading = ref(false)
    const pagination = ref({
      sortBy: 'updated',
      descending: false,
      page: 1,
      rowsPerPage: 16
    })
    const filter = ref({
      type: null,
      user_id: null,
      dateFilter: {
        from: null,
        to: null
      }
    })

    /**
     * Загрузить справочники
     *
     * @returns {Promise<void>}
     */
    async function fetchData() {
      loading.value = true
      await getEventTypes()
      await getUsers()
      loading.value = false
    }

    /**
     * Применить фильтры формы
     */
    function onApplyForm() {
      onRequest({pagination: pagination.value})
    }

    /**
     * При изменении состояния таблицы
     *
     * @param props
     * @returns {Promise<void>}
     */
    async function onRequest(props) {
      loading.value = true
      const {page, rowsPerPage, sortBy, descending} = props.pagination
      const params = {
        page,
        perPage: rowsPerPage,
        sort_by: sortBy,
        sort: descending ? 'desc' : 'asc',
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
      }

      for (let key in filter.value) {
        if (filter.value[key] !== null && typeof filter.value[key] !== 'object') {
          params[key] = filter.value[key]
        }
      }
      const {from, to} = filter.value.dateFilter
      const {data, meta} = await api.get('/events', {
        params: {
          ...params,
          ...from && to && {
            'period[0]': from,
            'period[1]': to
          }
        },
      }).then(({data}) => data)

      rows.value = data
      pagination.value.page = page
      pagination.value.sortBy = sortBy
      pagination.value.descending = descending
      pagination.value.rowsNumber = meta.total
      loading.value = false
    }

    /**
     * Справочники типов
     *
     * @returns {Promise<void>}
     */
    async function getEventTypes() {
      eventTypes.value = await api.get('/event-types').then(({data}) => data)
    }

    /**
     * Справочники пользователей
     *
     * @returns {Promise<void>}
     */
    async function getUsers() {
      const {data} = await api.get('/users').then(({data}) => data)
      users.value = data
    }

    onMounted(async () => {
      await fetchData()
      tableRef.value.requestServerInteraction()
    })

    return {
      tableRef,
      filter,
      pagination,
      loading,
      rows,
      tableColumns,
      eventTypes,
      users,

      onRequest,
      onApplyForm
    }
  }
}
</script>