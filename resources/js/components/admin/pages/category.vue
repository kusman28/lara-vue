<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal=true"><Icon type="md-add" /> Default </Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Icon</th>
								<th>Category Name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
							<tr v-for="(cat, i) in categoryLists" :key="i">
								<td>{{cat.id}}</td>
								<td class="table_image">
									<img :src="cat.iconImage" />
								</td>
								<td class="_table_name">{{cat.categoryName}}</td>
								<td>{{cat.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEdit(cat, i)">Edit</Button>
									<Button type="error" size="small" @click="deleteModalTag(cat, i)" :loading="cat.isDeleting">{{ cat.isDeleting ? 'Deleting' : 'Delete' }}</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<!-- Add Category -->
				<Modal
					v-model="addModal"
					title="Add Category"
					width="400"
					:mask-closable="false"
					:closable="false">

					<Input v-model="data.categoryName" placeholder="Enter Category name"/>

                    <div class="space"></div>
                    <Upload
                        ref="uploads"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :on-format-error="handleFormatError"
                        :on-error="handleError"
                        :max-size="2048"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

                    <div class="demo-upload-list" v-if="data.iconImage">
                            <img :src="`${data.iconImage}`">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                            </div>
                    </div>
                    <!-- <div class="thumbnail" v-if="`${data.iconImage}`">
                        <img :src="`/uploads/${data.iconImage}`" alt="">
                    </div> -->

					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding...' : 'Add Category'}}</Button>
					</div>
				</Modal>

				<!-- Edit Category -->
				<Modal
					v-model="editModal"
					title="Edit Category"
					width="400"
					:mask-closable="false"
					:closable="false">
					
					<Input v-model="editData.categoryName" placeholder="Enter Category name"/>

                    <div class="space"></div>
                    <Upload v-show="isIconImageNew"
                        ref="editDataUploads"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :on-format-error="handleFormatError"
                        :on-error="handleError"
                        :max-size="2048"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>

                    <div class="demo-upload-list" v-if="editData.iconImage">
                            <img :src="`${editData.iconImage}`">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                            </div>
                    </div>

					<div slot="footer">
						<Button type="default" @click="closeEdit = false">Close</Button>
						<Button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Editing...' : 'Save Category'}}</Button>
					</div>
				</Modal>

				<!-- Delete -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure to delete it?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag">Delete</Button>
					</div>
				</Modal>

			</div>
		</div>
    </div>
</template>

<script>
export default {
	data() {
		return {
			data: {
                categoryName: '',
                iconImage: ''
			},
			editData: {
				categoryName: '',
                iconImage: ''
			},
			addModal: false,
			editModal: false,
			deleteModal: false,
			isAdding: false,
			categoryLists: [

			],
			index: -1,
			isDeleting: false,
			deleteItem: {},
            i: -1,
			token: '',
			isIconImageNew: false,
			isEditingItem: false
		}
	},
	methods: {
		async addCategory() {
			if(this.data.categoryName.trim()=='') return this.error('Category name is required.')
			if(this.data.iconImage.trim()=='') return this.error('Icon image is required.')
			this.data.iconImage = `/uploads/${this.data.iconImage}`
			const res = await this.callApi('post', 'app/create_category', this.data)
			if (res.status===201) {
				this.categoryLists.unshift(res.data)
				this.success('Category has been added.')
				this.addModal = false
				this.data.categoryName = ''
				this.data.iconImage = ''
			} else {
				if (res.status == 422) {
					if (res.data.errors.categoryName) {
						this.error(res.data.errors.categoryName[0])
					}
					if (res.data.errors.iconImage) {
						this.error(res.data.errors.iconImage[0])
					}
				} else {
					this.swr()
				}
			}
		},
		async editCategory() {
			if(this.editData.categoryName.trim()=='') return this.error('Category name is required.')
			if(this.editData.iconImage.trim()=='') return this.error('Icon image is required.')
			const res = await this.callApi('post', 'app/edit_category', this.editData)
			if (res.status===200) {
				this.categoryLists[this.index].categoryName = this.editData.categoryName
				this.success('Category has been saved.')
				this.editModal = false
			} else {
				if (res.status == 422) {
					if (res.data.errors.categoryName) {
						this.error(res.data.errors.categoryName[0])
					}
					if (res.data.errors.iconImage) {
						this.error(res.data.errors.iconImage[0])
					}
				} else {
					this.swr()
				}
			}
		},
		showEdit(category, index) {
			// let obj = {
			// 	id: tag.id,
			// 	tagName: tag.tagName
			// }
			this.editData = category
			this.editModal = true
			this.index = index
			this.isEditingItem = true
		},
		async deleteTag() {
			this.isDeleting = true
			const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
			if (res.status === 200) {
				this.tags.splice(this.i, 1)
				this.success('Tag has been deleted.')
			} else {
				this.swr()
			}
			this.isDeleting = false
			this.deleteModal = false
		},
		deleteModalTag(tag, i) {
			this.deleteItem = tag
			this.i = i
			this.deleteModal = true
        },
        handleSuccess (res, file) {
			res = `/uploads/${res}`
			if(this.isEditingItem)
			{
				return this.editData.iconImage = res
			}
            this.data.iconImage = res
        },
        handleError (res, file) {
            console.log('res', res)
            console.log('file', file)
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong.'}`
            });
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        async deleteImage(isAdd=true) {
			let image
			if(!isAdd){
				this.isIconImageNew = true
				image = this.editData.iconImage
				this.editData.iconImage = ''
				this.$refs.editDataUploads.clearFiles()
			}else{
				image = this.data.iconImage
				this.data.iconImage = ''
				this.$refs.uploads.clearFiles()
			}
            const res = await this.callApi('post', 'app/delete_image', {imageName: image})
            if (res.status !== 200) {
                this.data.iconImage = image
                this.swr()
            }
		},
		closeEdit() {
			this.isEditingItem = false
			this.editModal = false
		}
	},
    async created() {
        this.token = window.Laravel.csrfToken
		const res = await this.callApi('get', 'app/created_category');
		if (res.status == 200) {
			this.categoryLists = res.data
		} else {
			this.swr()
		}
	}
}
</script>
<style>
    .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>