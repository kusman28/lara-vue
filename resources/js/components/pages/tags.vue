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
									<Button type="info" size="small">Edit</Button>
									<Button type="error" size="small">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<!-- Tag Modal -->
				<Modal
					v-model="addModal"
					title="Add Tag"
					:mask-closable="false"
					:closable="false">
					<Input v-model="data.tagName" placeholder="Enter Tag name"/>

					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding...' : 'Save'}}</Button>
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
			addModal: false,
			isAdding: false,
			tags: [

			]
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
				this.swr()
			}
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