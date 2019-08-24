<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12 mt-3">
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div
            class="widget-user-header text-white"
            style="background: url('/images/admin/logo.png') center center;"
          >
            <h3 class="widget-user-username text-info">{{form.name}}</h3>
            <h5 class="widget-user-desc">Web Designer</h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle" :src="getProfileImg()" alt="User Avatar" />
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">3,200</h5>
                  <span class="description-text">SALES</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">13,000</h5>
                  <span class="description-text">FOLLOWERS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4">
                <div class="description-block">
                  <h5 class="description-header">35</h5>
                  <span class="description-text">PRODUCTS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link" href="#activity" data-toggle="tab">Activity</a>
              </li>

              <li class="nav-item">
                <a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
              </li>
            </ul>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <!-- /.tab-pane -->

              <div class="tab-pane active" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input
                        type="text"
                        class="form-control"
                        id="name"
                        placeholder="Name"
                        v-model="form.name"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input
                        type="email"
                        class="form-control"
                        id="email"
                        v-model="form.email"
                        placeholder="Email"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="phone" class="col-sm-2 control-label">Phone</label>

                    <div class="col-sm-10">
                      <input
                        type="text"
                        class="form-control"
                        id="phone"
                        placeholder="Phone"
                        v-model="form.phone"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="address" class="col-sm-2 control-label">Address</label>

                    <div class="col-sm-10">
                      <textarea
                        class="form-control"
                        id="address"
                        placeholder="Address"
                        v-model="form.address"
                      ></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="roles" class="col-sm-2 control-label">roles</label>

                    <div class="col-sm-10">
                      <input
                        type="text"
                        class="form-control"
                        id="roles"
                        placeholder="roles"
                        v-model="form.roles"
                      />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="img" class="col-sm-2 control-label">Image</label>
                    <div class="col-sm-10">
                      <input type="file" name="img" id="img" @change="updateProfile" />
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" /> I agree to the
                          <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button
                        @click.prevent="updateInfo"
                        type="submit"
                        class="btn btn-outline-info"
                      >Update</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
export default {
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        email: "",
        phone: "",
        address: "",
        password: "",
        roles: "",
        img: ""
      })
    };
  },
  methods: {
    getProfileImg() {
      return this.form.img.indexOf("base64") != -1
        ? this.form.img
        : "../../../public/images/profile/" + this.form.img;
    },
    updateInfo() {
      this.$Progress.start();
      this.form
        .put("api/profile")
        .then(() => {
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    updateProfile(e) {
      let file = e.target.files[0];

      let reader = new FileReader();
      if (file["size"] < 2111775) {
        reader.onloadend = file => {
          // console.log("RESULT", reader.result);
          this.form.img = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        Swal({
          type: "error",
          title: "Oops...",
          text: "This image is too long to upload!"
        });
      }
    }
  },
  created() {
    axios.get("api/profile").then(({ data }) => this.form.fill(data));
  }
};
</script>
