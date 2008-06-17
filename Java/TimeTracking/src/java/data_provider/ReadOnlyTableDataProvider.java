/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package data_provider;

import beans.UserBean;
import com.sun.data.provider.DataProviderException;
import com.sun.data.provider.FieldKey;
import com.sun.data.provider.RowKey;
import com.sun.data.provider.impl.AbstractTableDataProvider;

/**
 *
 * @author manuel
 */
public class ReadOnlyTableDataProvider extends AbstractTableDataProvider
{

    public ReadOnlyTableDataProvider()
    {
        //addFieldKeys();
    }
    
    
    @Override
    public Class getType(FieldKey fieldKey) throws DataProviderException
    {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    @Override
    public boolean isReadOnly(FieldKey fieldKey) throws DataProviderException
    {
        return true;
    }

    @Override
    public int getRowCount() throws DataProviderException
    {
        return rowKeyList.size();
    }

    @Override
    public Object getValue(FieldKey fieldKey, RowKey row) throws DataProviderException
    {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    @Override
    public void setValue(FieldKey fieldKey, RowKey row, Object value) throws DataProviderException
    {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    @Override
    public boolean canInsertRow(RowKey beforeRow) throws DataProviderException
    {
        return false;
    }

    @Override
    public RowKey insertRow(RowKey beforeRow) throws DataProviderException
    {
        throw new UnsupportedOperationException("Not supported yet.");
    }

    @Override
    public boolean canAppendRow() throws DataProviderException
    {
        return true;
    }

    @Override
    public RowKey appendRow() throws DataProviderException
    {
        throw new UnsupportedOperationException("Not supported yet.");
    }
    
    public RowKey appendRow(UserBean bean)
    {
        RowKey key = new RowKey(bean.getClass().getName() + bean.getMid());
        rowKeyMap.put(key, bean);
        rowKeyList.add(key);
        return key;
    }

    @Override
    public boolean canRemoveRow(RowKey row) throws DataProviderException
    {
       return false;
    }

    @Override
    public void removeRow(RowKey row) throws DataProviderException
    {
        throw new UnsupportedOperationException("Not supported yet.");
    }

}
