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
								<th>Tag Name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
							<tr v-for="(tag, i) in tags" :key="i">
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEdit(tag, i)">Edit</Button>
									<Button type="error" size="small" @click="deleteModalTag(tag, i)" :loading="tag.isDeleting">{{ tag.isDeleting ? 'Deleting' : 'Delete' }}</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<!-- Add Tag -->
				<Modal
					v-model="addModal"
					title="Add Tag"
					width="400"
					:mask-closable="false"
					:closable="false">
					<Input v-model="data.tagName" placeholder="Enter Tag name"/>

					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding' : 'Save'}}</Button>
					</div>
				</Modal>

				<!-- Edit Tag -->
				<Modal
					v-model="editModal"
					title="Edit Tag"
					width="400"
					:mask-closable="false"
					:closable="false">
					<Input v-model="editData.tagName" placeholder="Enter Tag name"/>

					<div slot="footer">
						<Button type="default" @click="editModal = false">Close</Button>
						<Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Editing...' : 'Save'}}</Button>
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
				tagName: '',
			},
			editData: {
				tagName: ''
			},
			addModal: false,
			editModal: false,
			deleteModal: false,
			isAdding: false,
			tags: [

			],
			index: -1,
			isDeleting: false,
			deleteItem: {},
			i: -1
		}
	},
	methods: {
		async addTag() {
			if(this.data.tagName.trim()=='') return this.error('Tag name is required.')
			const res = await this.callApi('post', 'app/created_tag', this.data)
			if (res.status===201) {
				this.tags.unshift(res.data)
				this.success('Tag has been added.')
				this.addModal = false
				this.data.tagName = ''
			} else {
				if (res.status == 422) {
					if (res.data.errors.tagName) {
						this.error(res.data.errors.tagName[0])
					}
				} else {
					this.swr()
				}
			}
		},
		async editTag() {
			if(this.editData.tagName.trim()=='') return this.error('Tag name is required.')
			const res = await this.callApi('post', 'app/edit_tag', this.editData)
			if (res.status===200) {
				this.tags[this.index].tagName = this.editData.tagName
				this.success('Tag has been saved.')
				this.editModal = false
			} else {
				if (res.status == 422) {
					if (res.editData.errors.tagName) {
						this.error(res.editData.errors.tagName[0])
					}
				} else {
					this.swr()
				}
			}
		},
		showEdit(tag, index) {
			let obj = {
				id: tag.id,
				tagName: tag.tagName
			}
			this.editData = obj
			this.editModal = true
			this.index = index
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
		}
	},
    async created() {
		const res = await this.callApi('get', 'app/created_tag');
		if (res.status == 200) {
			this.tags = res.data
		} else {
			this.swr()
		}
	}
}
</script>