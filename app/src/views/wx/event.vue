<template>
  <div>
    <mu-container>
      <mu-expansion-panel v-for="(name, key) in names" :key="key" :expand="panel === key" @change="toggle(key)">
        <div slot="header">
          {{ name }}
        </div>
        <mu-form :model="form">
          <mu-form-item label-float label="规则关键字" prop="mp_rule_keywords" :rules="rules.mp_rule_keywords">
            <mu-text-field v-model="form.mp_rule_keywords" prop="username" />
          </mu-form-item>
          <mu-form-item label="状态">
            <mu-radio v-for="(i, j) in statusIndex" :key="j" v-model.number="form.status" :value="parseInt(j)" :label="i" />
          </mu-form-item>
        </mu-form>
        <mu-button slot="action" flat color="primary" @click="updateItem">
          保存
        </mu-button>
      </mu-expansion-panel>
    </mu-container>
  </div>
</template>

<script>
import { getEventList, updateEvent } from '../../api/wx'
import check from './check'

const defaultForm = {
  id: null,
  mp_rule_keywords: null,
  name: null,
  status: 2
}

export default {
  data() {
    return {
      panel: '',
      items: null,
      names: [],
      statusIndex: {},
      form: Object.assign({}, defaultForm),
      rules: {
        mp_rule_keywords: [
          { validate: (val) => !!val, message: '不能为空' }
        ]
      }
    }
  },
  created() {
    check()
    this.fetchData()
  },
  methods: {
    fetchData() {
      getEventList().then(response => {
        this.names = response.names
        this.items = response.items
        this.statusIndex = response.statusIndex
      })
    },
    toggle(panel) {
      this.panel = panel === this.panel ? '' : panel
      this.form = this.items[panel] ? this.items[panel] : Object.assign({}, defaultForm, { name: panel })
    },
    updateItem() {
      updateEvent(this.form).then(response => {
        this.items[this.panel] = response
        this.form = response
        this.$toast.success('保存成功!')
      })
    }
  }
}
</script>

<style scoped>

</style>
