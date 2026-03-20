export namespace main {
	
	export class BeverageResult {
	    success: boolean;
	    data: any;
	    error?: string;
	
	    static createFrom(source: any = {}) {
	        return new BeverageResult(source);
	    }
	
	    constructor(source: any = {}) {
	        if ('string' === typeof source) source = JSON.parse(source);
	        this.success = source["success"];
	        this.data = source["data"];
	        this.error = source["error"];
	    }
	}

}

