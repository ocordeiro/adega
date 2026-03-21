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
	export class SettingsResult {
	    logo_url?: string;
	    color_primary: string;
	    color_secondary: string;
	    color_background: string;
	    color_text: string;
	    element_scale: number;
	    font_scale: number;

	    static createFrom(source: any = {}) {
	        return new SettingsResult(source);
	    }

	    constructor(source: any = {}) {
	        if ('string' === typeof source) source = JSON.parse(source);
	        this.logo_url = source["logo_url"];
	        this.color_primary = source["color_primary"];
	        this.color_secondary = source["color_secondary"];
	        this.color_background = source["color_background"];
	        this.color_text = source["color_text"];
	        this.element_scale = source["element_scale"];
	        this.font_scale = source["font_scale"];
	    }
	}

}

