// Alamat tujuan arduino mengirim data
use Illuminate\Http\Request;
use App\Models\EnergiListrik;
use Illuminate\Support\Facades\Route;

Route::get('/simpan-data', function (Request $request) {
    EnergiListrik::create($request->all());
    return response()->json(['status' => 'success']);
});