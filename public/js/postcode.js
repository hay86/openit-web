/**
 * Created by xukf on 04/03/2017.
 */
var postcode = {"上海":{"上海市": {"闵行区": 201100, "虹口区": 200080, "杨浦区": 200082, "黄浦区": 200001, "青浦区": 201700, "普陀区": 200333, "长宁区": 200050, "崇明县": 202150, "浦东新区": 200120, "松江区": 201600, "宝山区": 201900, "奉贤区": 201400, "嘉定区": 201800, "金山区": 201500, "徐汇区": 200030, "静安区": 200040}}};
function kf_province() { return $.map(postcode, function(v,k){ return k;}); }
function kf_city(province) { return $.map(postcode[province], function(v,k){ return k;}); }
function kf_district(province, city) { return $.map(postcode[province][city], function(v,k){ return k;}); }
function kf_postcode(province, city, district) { return postcode[province][city][district]; }
var provabbr = {"北京市":"北京","天津市":"天津","上海市":"上海","重庆市":"重庆","河北省":"河北","山西省":"山西","辽宁省":"辽宁","吉林省":"吉林","黑龙江省":"黑龙江","江苏省":"江苏","浙江省":"浙江","安徽省":"安徽","福建省":"福建","江西省":"江西","山东省":"山东","河南省":"河南","湖北省":"湖北","湖南省":"湖南","广东省":"广东","海南省":"海南","四川省":"四川","贵州省":"贵州","云南省":"云南","陕西省":"陕西","甘肃省":"甘肃","青海省":"青海","内蒙古自治区":"内蒙古","西藏自治区":"西藏","新疆维吾尔自治区":"新疆","宁夏回族自治区":"宁夏","广西壮族自治区":"广西","香港特别行政区":"香港","澳门特别行政区":"澳门"};
function kf_provabbr(province) { return provabbr[province]; }