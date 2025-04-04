<div class="mb-3">
    <label>Nome</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $insumo->nome ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Tipo</label>
    <select name="tipo" class="form-control" required>
        <option value="insumo" {{ (old('tipo', $insumo->tipo ?? '') == 'insumo') ? 'selected' : '' }}>Insumo</option>
        <option value="medicamento" {{ (old('tipo', $insumo->tipo ?? '') == 'medicamento') ? 'selected' : '' }}>Medicamento</option>
    </select>
</div>

<div class="mb-3">
    <label>Quantidade Mínima</label>
    <input type="number" name="quantidade_minima" class="form-control" value="{{ old('quantidade_minima', $insumo->quantidade_minima ?? 0) }}" required>
</div>

<div class="mb-3">
    <label>Unidade de Medida</label>
    <input type="text" name="unidade_medida" class="form-control" value="{{ old('unidade_medida', $insumo->unidade_medida ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Quantidade Existente</label>
    <input type="number" name="quantidade_existente" class="form-control" value="{{ old('quantidade_existente', $insumo->quantidade_existente ?? 0) }}" required>
</div>
