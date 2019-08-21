<template>
  <div class="container">
    <div class="row justify-content-center" v-if="$gate.isAdmin()">
      <div class="col-md-12 col-sm-12">
        <div class="card" style="width:100%; height:100%;">
          <div class="card-header">
            <h3 class="card-title">User table</h3>
            <div class="card-tools">
              <button class="btn btn-outline-success" @click="newModal">
                <i class="fa fa-user-plus blue"></i>Thêm mới
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Họ Tên</th>
                  <th>Email</th>
                  <th>Số ĐT</th>
                  <th>Địa chỉ</th>
                  <th>Quyền</th>
                  <th>Tùy chọn</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user , index ) in users" :key="index">
                  <td>{{user.id }}</td>
                  <td>{{user.name | upText}}</td>
                  <td>{{user.email }}</td>
                  <td>
                    <span class="tag tag-success">{{user.phone}}</span>
                  </td>
                  <td>{{user.address}}</td>
                  <td>{{user.role }}</td>
                  <td>
                    <a href="#" @click="editModal(user)" title="edit">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    <a href="#" @click="deleteUser(user.id)" title="delete" v-if="$gate.isAdmin()">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- model create -->
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewUser"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              id="title-action"
            >{{(actionMode=='create')?'Thêm mới':'Cập nhật'}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent=" (actionMode == 'create') ? createUser() : updateUser()">
            <div class="modal-body">
              <div class="form-group">
                <label>Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input
                  v-model="form.email"
                  type="text"
                  name="email"
                  placeholder="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                />
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group">
                <label>Phone</label>
                <input
                  v-model="form.phone"
                  type="text"
                  name="phone"
                  placeholder="phone"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('phone') }"
                />
                <has-error :form="form" field="phone"></has-error>
              </div>
              <div class="form-group" v-if="actionMode=='create'">
                <label>Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  placeholder="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                />
                <has-error :form="form" field="password"></has-error>
              </div>
              <div class="form-group">
                <select
                  class="form-control"
                  id="role"
                  v-model="form.role"
                  name="role"
                  :class="{ 'is-invalid': form.errors.has('role') }"
                >
                  <option value>--select Role --</option>
                  <option value="Admin">Admin</option>
                  <option value="User">User</option>
                  <option value="Author">Author</option>
                </select>
                <has-error :form="form" field="role"></has-error>
              </div>
              <div class="form-group">
                <label>Address</label>
                <textarea
                  v-model="form.address"
                  type="text"
                  name="address"
                  placeholder="address"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('address') }"
                ></textarea>
                <has-error :form="form" field="address"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy</button>
              <button
                type="submit"
                class="btn btn-primary"
              >{{(actionMode=='create')?'Thêm mới':'Cập nhật'}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      actionMode: "create",
      form: new Form({
        id: "",
        name: "",
        email: "",
        phone: "",
        address: "",
        password: "",
        role: ""
      }),
      users: {}
    };
  },
  methods: {
    newModal() {
      this.actionMode = "create";
      this.form.reset();
      $("#addNew").modal("show");
    },
    editModal(user) {
      this.actionMode = "update";
      this.form.reset();
      this.form.clear();
      $("#addNew").modal("show");
      this.form.fill(user);
      this.form.password = "";
    },
    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          //sucsecss

          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "Update in successfully"
          });
          this.$emit("AfterCreated");
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    createUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(() => {
          this.$emit("AfterCreated");
          $("#addNew").modal("hide");
          toast.fire({
            type: "success",
            title: "Signed in successfully"
          });

          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    loadUser() {
      if (this.$gate.isAdmin) {
        axios.get("api/user").then(({ data }) => (this.users = data.data));
      }
    },
    deleteUser(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        //sen request
        if (result.value) {
          this.form
            .delete("api/user/" + id)
            .then(() => {
              this.$emit("AfterCreated");
              Swal.fire("Deleted!", "Your file has been deleted.", "success");
            })
            .catch(() => {
              Swal.fire({
                type: "error",
                title: "Oops...",
                text: "Something went wrong!",
                footer: "<a href>Why do I have this issue?</a>"
              });
            });
        }
      });
    }
  },
  created() {
    this.loadUser();

    this.$on("AfterCreated", () => {
      this.loadUser();
    });
  }
};
</script>
