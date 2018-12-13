<template>
  <div
    class="login-page"
  >
    <mu-container>
      <mu-paper class="login-box" :z-depth="5">
        <div class="text-center login-title">
          登 录
        </div>
        <mu-form ref="form" :model="validateForm" class="login-form">
          <mu-form-item
            label-float
            icon="face"
            label="用户名"
            prop="username"
            :rules="usernameRules"
            class="login-item"
          >
            <mu-text-field v-model="validateForm.username" prop="username" />
          </mu-form-item>
          <mu-form-item label-float icon="lock_outline" label="密码" prop="password" :rules="passwordRules" class="login-item">
            <mu-text-field
              v-model="validateForm.password"
              prop="password"
              :action-icon="visibility ? 'visibility_off' : 'visibility'"
              :action-click="() => (visibility = !visibility)"
              :type="visibility ? 'text' : 'password'"
            />
          </mu-form-item>
          <mu-form-item class="text-center">
            <mu-button v-loading="loading" :disabled="loading" color="primary" @click="submit">
              提交
            </mu-button>
            <mu-button @click="clear">
              重置
            </mu-button>
          </mu-form-item>
        </mu-form>
      </mu-paper>
    </mu-container>
  </div>
</template>

<script>
export default {
  name: 'Index',
  data() {
    return {
      usernameRules: [
        { validate: (val) => !!val, message: '必须填写用户名' }
      ],
      passwordRules: [
        { validate: (val) => !!val, message: '必须填写密码' }
      ],
      validateForm: {
        username: 'admin',
        password: '123456'
      },
      visibility: false,
      loading: false
    }
  },
  methods: {
    submit() {
      this.$refs.form.validate().then((result) => {
        if (result) {
          this.loading = true
          this.$store.dispatch('Login', this.validateForm).then(() => {
            this.loading = false
            this.$router.push({ path: '/' })
          }).catch(() => {
            this.loading = false
            this.$toast.error('接口调用失败')
          })
        }
      })
    },
    clear() {
      this.$refs.form.clear()
      this.validateForm = {
        username: '',
        password: ''
      }
    }
  }
}
</script>

<style scoped>
  .login-page {
    position: relative;
    height: 100vh;
    display: flex!important;
    align-items: center;
    margin: 0;
    border: 0;
    background-size: cover;
    background-position: top center;
    background-image: url("../../assets/login-page.jpg");
  }
  .login-page:before {
    position: absolute;
    width: 100%;
    height: 100%;
    display: block;
    left: 0;
    top: 0;
    content: "";
  }
  .login-box {
    z-index: 2;
    padding-top: 30px;
    padding-bottom: 20px;
    background-color: #fff;
    position: relative;
    width: 400px;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
  }
  .login-title {
    font-size: 20px;
    color: #999;
    margin-bottom: 20px;
  }
  .login-item {
    width: 100%;
    padding-right: 25px;
  }
  .text-center /deep/  .mu-form-item-content {
    justify-content: center!important;
  }
</style>
