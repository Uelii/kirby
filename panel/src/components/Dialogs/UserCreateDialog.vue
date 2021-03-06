<template>
  <k-dialog
    ref="dialog"
    :button="$t('create')"
    size="medium"
    theme="positive"
    @submit="$refs.form.submit()"
    @close="reset"
  >
    <k-form
      ref="form"
      :fields="fields"
      :novalidate="true"
      v-model="user"
      @submit="create"
    />
  </k-dialog>
</template>

<script>
import DialogMixin from "@/mixins/dialog.js";

export default {
  mixins: [DialogMixin],
  data() {
    return {
      user: this.emptyUser(),
      languages: [],
      roles: []
    };
  },
  computed: {
    fields() {
      return {
        name: {
          label: this.$t("name"),
          type: "text",
          icon: "user",
        },
        email: {
          label: this.$t("email"),
          type: "email",
          icon: "email",
          link: false,
          required: true
        },
        password: {
          label: this.$t("password"),
          type: "password",
          icon: "key",
        },
        language: {
          label: this.$t("language"),
          type: "select",
          icon: "globe",
          options: this.languages,
          required: true,
          empty: false
        },
        role: {
          label: this.$t("role"),
          type: this.roles.length === 1 ? "hidden" : "radio",
          required: true,
          options: this.roles
        }
      };
    }
  },
  methods: {
    create() {
      this.$api.users
        .create(this.user)
        .then(() => {
          this.success({
            message: ":)",
            event: "user.create"
          });
        })
        .catch(error => {
          this.$refs.dialog.error(error.message);
        });
    },
    emptyUser() {
      return {
        name: "",
        email: "",
        password: "",
        language: "en",
        role: "admin"
      };
    },
    open() {
      this.$api.roles.options()
        .then(roles => {
          this.roles = roles;

          // load all translations
          this.$api.translations.options().then(languages => {
            this.languages = languages;
            this.$refs.dialog.open();
          })
          .catch (error => {
            this.$store.dispatch('notification/error', error);
          });

        })
        .catch(error => {
          this.$store.dispatch('notification/error', error);
        });
    },
    reset() {
      this.user = this.emptyUser();
    }
  }
};
</script>
